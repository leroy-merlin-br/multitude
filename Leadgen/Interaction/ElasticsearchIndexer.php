<?php
namespace Leadgen\Interaction;

use Elasticsearch\Client;
use MongoDB\Driver\WriteConcern;
use MongoDB\BSON\ObjectID;

/**
 * Index `Interaction`s into Elasticsearch
 */
class ElasticsearchIndexer
{
    /**
     * Elasticsearch client
     * @var Client
     */
    protected $elasticsearch;

    /**
     * Constructs a new instance
     * @param Client $elasticsearch Elasticsearch Client to be injected.
     */
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Index a set of interactions into elasticsearch
     *
     * @param  Interaction[] $interactions Interactions to be indexed
     *
     * @return boolean Success
     */
    public function index($interactions)
    {
        $result = $this->builkIndex($interactions);

        $acknowledgedItems = [];
        foreach (($result['items'] ?? []) as $sentItem) {
            if (($sentItem['index']['status'] ?? 400) == 200) {
                $acknowledgedItems[] = new ObjectID($sentItem['index']['_id'] ?? null);
            }
        }

        $this->markAsAknowledged($acknowledgedItems);

        return $acknowledgedItems;
    }

    protected function builkIndex($interactions)
    {
        $indexName = app('config')->get('elasticsearch.defaultIndex', 'main');

        $params = ['body' => []];

        foreach ($interactions as $interaction) {
            $params['body'][] = [
                'index' => [
                    '_index' => $indexName,
                    '_type' => 'Interaction',
                    '_id' => (string) $interaction->_id,
                ]
            ];

            $params['body'][] = $this->parseInteraction($interaction);
        }

        return $this->elasticsearch->bulk($params);
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

    /**
     * Parses the fields of an Interaction in order to retrieve
     *
     * @param  Interaction $interaction Interacrion that will have it's attributes retrieved
     *
     * @return array
     */
    protected function parseInteraction(Interaction $interaction)
    {
        $document = array_diff_key($interaction->attributes, ['_id' => 1]);
        $document['created_at'] = $interaction->created_at->toDateTime()->format('Y-m-d\Th:i');
        $document['updated_at'] = $interaction->updated_at->toDateTime()->format('Y-m-d\Th:i');

        return $document;
    }
}
