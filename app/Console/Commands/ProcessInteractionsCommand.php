<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Leadgen\Customer\ElasticsearchIndexer as CustomerIndexer;
use Leadgen\Customer\InteractionsParser;
use Leadgen\Interaction\ElasticsearchIndexer as InteractionIndexer;
use Leadgen\Interaction\Interaction;
use Leadgen\Interaction\Repository;
use MongoDB\Driver\WriteConcern;
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
     * @var \Leadgen\Interaction\ElasticsearchIndexer
     */
    protected $interactionIndexer;

    /**
     * Indexer instance that will be used to index sustomers into Elasticsearch
     * @var \Leadgen\Customer\ElasticsearchIndexer
     */
    protected $customerIndexer;

    /**
     * Customer interaction parser instance that will be used process interactions to the Customers.
     * @var InteractionParser
     */
    protected $interactionParser;

    public function __construct(
        Repository $interactionRepo,
        InteractionIndexer $interactionIndexer,
        InteractionsParser $interactionParser,
        CustomerIndexer $customerIndexer
    ) {
        parent::__construct();

        $this->interactionRepo = $interactionRepo;
        $this->interactionIndexer = $interactionIndexer;
        $this->interactionParser = $interactionParser;
        $this->customerIndexer = $customerIndexer;
    }

    /**
     * Performs the command
     */
    public function fire()
    {
        $interactions = $this->interactionRepo->getUnacknowledged()->all();

        if ($count = count($interactions)) {
            $processedIds = $this->interactionIndexer->index($interactions);
            $customers    = $this->interactionParser->parse($interactions);

            $this->markAsAknowledged($processedIds);
            $this->customerIndexer->index($customers);

            $this->comment("$count interactions processed");
        }
    }

    /**
     * Mark a series of `Interaction`s as aknowledged
     *
     * @param  array  $idList List of _id of interactions
     *
     * @return boolean  Success
     */
    protected function markAsAknowledged(array $idList)
    {
        (new Interaction)->collection()->updateMany(
            [
                '_id' => [
                    '$in' => $idList,
                ],
            ],
            [
                '$set' => [
                    'acknowledged' => true,
                ],
            ],
            ['writeConcern' => new WriteConcern(1)]
        );

        return true;
    }
}
