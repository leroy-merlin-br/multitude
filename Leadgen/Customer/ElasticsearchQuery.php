<?php
namespace Leadgen\Customer;

use Elasticsearch\Client;
use MongoDB\Driver\WriteConcern;
use MongoDB\BSON\ObjectID;

/**
 * Perform queries into Elasticsearch
 */
class ElasticsearchQuery
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
     * Retrieves customers from Elasticsearch matching the given query
     *
     * @param  array $query Elasticsearch query body
     *
     * @return array results
     */
    public function get($query)
    {
        $indexName = app('config')->get('elasticsearch.defaultIndex', 'main');

        $params = [
            'index' => $indexName,
            'type' => 'Customer',
            'body' => $query
        ];

        // return $params;

        return $this->elasticsearch->search($params);
    }
}
