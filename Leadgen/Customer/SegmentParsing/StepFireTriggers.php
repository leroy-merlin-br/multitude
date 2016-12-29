<?php
namespace Leadgen\Customer\SegmentParsing;

use Leadgen\Customer\Repository as CustomerRepository;
use PHProutine\Channel;
use PHProutine\Runner;

/**
 * This SegmentParser step fire the triggers of the segment for the customerIds
 * that are in the Dto.
 *
 * @see SegmentParser
 */
class StepFireTriggers extends StepBase
{
    /**
     * PHProutine runner instance. In order to execute code asynchronously
     * @var Runner
     */
    protected $phproutine;

    /**
     * @var CustomerRepository
     */
    protected $customerRepo;

    /**
     * Injects dependencies
     * @param Runner             $phproutine   PHPRoutine runner.
     * @param CustomerRepository $customerRepo CustomerRepository that will be used to get the Customer cursor for the trigger.
     */
    public function __construct(Runner $phproutine, CustomerRepository $customerRepo)
    {
        $this->phproutine = $phproutine;
        $this->customerRepo = $customerRepo;
    }

    /**
     * Process this step of the chain.
     *
     * @param  Dto $dto The input data.
     * @return Dto $dto after parsing.
     */
    protected function process(Dto $dto): Dto
    {
        if (! isset($dto->customerIds) || ! isset($dto->segment)) {
            return $dto;
        }

        $dto->triggerResult = new Channel;

        $this->fireTriggers(
            $dto->triggerResult,
            $dto->segment->triggers(),
            $dto->customerIds
        );

        return $dto;
    }

    /**
     * Fires the triggers of the given segment and writes the results in the
     * given channel.
     * WARNING: This method is executed asynchronously throught a PHPRoutine.
     *
     * @see https://github.com/polonskiy/phproutine
     *
     * @param  Channel $channel     Channel object where the results will be written in.
     * @param  mixed   $triggers    Array or iterable of Trigger objects.
     * @param  array   $customerIds The ids of the customers that were affected.
     *
     * @return void
     */
    protected function fireTriggers(Channel $channel, $triggers, array $customerIds)
    {
        if ((is_array($triggers) && empty($triggers)) || is_object($triggers) && $triggers->count() < 1) {
            return;
        }

        $async = function ($ch, $triggers, $customerIds) {
            $customers = $this->customerRepo->where(['_id' => ['$in' => $customerIds]]);

            foreach ($triggers as $trigger) {
                $results = app()->make($trigger->type)->fireTrigger($customers, $trigger->settings);
                $ch->write(serialize($results));
            }

            $ch->write(serialize(null));
        };

        $this->phproutine->go($async, $channel, $triggers, $customerIds);
    }
}
