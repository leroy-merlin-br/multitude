<?php

namespace Leadgen\ScheduledDump;

use Leadgen\Interaction\Repository as InteractionRepo;
use Leadgen\ScheduledDump\Connectors\Sftp;

/**
 * A service that, by receiving an scheduled dump instance, will perform the
 * corresponding interaction data dump to the specified external resource.
 *
 * The DumpExecutor is the entry point when talking about actually executing
 * an action within this namespace.
 */
class DumpExecutor
{
    /**
     * Repository of interactions that will be used.
     *
     * @var Repository
     */
    protected $interactionRepo;

    /**
     * Injects dependencies
     * @param InteractionRepo $interactionRepo Repository of interactions that will be used.
     */
    public function __construct(InteractionRepo $interactionRepo)
    {
        $this->interactionRepo = $interactionRepo;
    }

    /**
     * Executes the given ScheduledDump
     *
     * @param  ScheduledDump $dump Scheduled dump to be executed.
     *
     * @return integer Amount of interactions that were written in the external resource.
     */
    public function execute(ScheduledDump $dump)
    {
        $connector = new Sftp;
        $connector->configure($dump->settings);

        $interactions = $this->interactionRepo->getPeriod('-1 day');
        $count = $interactions->count();

        if ($connector->dump($interactions)) {
            return $count;
        }

        return 0;
    }
}
