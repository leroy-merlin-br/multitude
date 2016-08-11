<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Leadgen\Interaction\ElasticsearchIndexer;
use Leadgen\Interaction\Repository;
use Swagger;
use Symfony\Component\Console\Input\InputArgument;

class ProcessInteractionsCommand extends Command
{
    /**
     * Command Name.
     * @var string
     */
    protected $name = 'leadgen:proc-interaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process newly added interactions.';

    /**
     * Repository of interactions that will be used
     * @var Repository
     */
    protected $interactionRepo;

    /**
     * Indexer instance that will be used to index interactions into Elasticsearch
     * @var ElasticsearchIndexer
     */
    protected $esIndexer;

    public function __construct(Repository $interactionRepo, ElasticsearchIndexer $esIndexer)
    {
        parent::__construct();

        $this->interactionRepo = $interactionRepo;
        $this->esIndexer = $esIndexer;
    }

    /**
     * Performs the command
     */
    public function fire()
    {
        $interactions = $this->interactionRepo->getUnacknowledged();

        if ($count = $interactions->count()) {
            $processedIds = $this->esIndexer->index($interactions);
            $this->comment("$count interactions processed");
        }
    }

}
