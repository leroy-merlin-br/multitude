<?php

namespace Leadgen\Customer;

use Elasticsearch\Client;
use Leadgen\Interaction\ElasticsearchCaster;
use MongoDB\BSON\ObjectID;
use Mongolid\Util\ObjectIdUtils;

/**
 * Index `Customer`s into Elasticsearch.
 */
class ElasticsearchIndexer
{
    /**
     * Elasticsearch client.
     *
     * @var Client
     */
    protected $elasticsearch;

    /**
     * Constructs a new instance.
     *
     * @param Client $elasticsearch Elasticsearch Client to be injected.
     */
    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Index a set of customers into elasticsearch.
     *
     * @param Customer[] $customers Customers to be indexed
     *
     * @return bool Success
     */
    public function index($customers)
    {
        $result = $this->builkIndex($customers);

        $acknowledgedItems = [];
        foreach (($result['items'] ?? []) as $sentItem) {
            if (($sentItem['index']['status'] ?? 400) > 299) {
                throw new \Exception('Unable to index Customer '.json_encode($sentItem['index']), 1);
            }

            if (ObjectIdUtils::isObjectId($sentItem['index']['_id'])) {
                $acknowledgedItems[] = new ObjectID($sentItem['index']['_id']);
            } else {
                $acknowledgedItems[] = $sentItem['index']['_id'];
            }
        }

        return $acknowledgedItems;
    }

    protected function builkIndex($customers)
    {
        $indexName = app('config')->get('elasticsearch.defaultIndex', 'main');

        $params = ['body' => []];

        foreach ($customers as $customer) {
            $params['body'][] = [
                'index' => [
                    '_index' => $indexName,
                    '_type'  => 'Customer',
                    '_id'    => (string) $customer->_id,
                ],
            ];

            $params['body'][] = $this->parseCustomer($customer);
        }

        return $this->elasticsearch->bulk($params);
    }

    /**
     * Parses the fields of an Customer in order to retrieve.
     *
     * @param Customer $customer Customer that will have it's attributes retrieved
     *
     * @return array
     */
    protected function parseCustomer(Customer $customer)
    {
        $document = array_diff_key($customer->attributes, ['_id' => 1, 'customers' => 1]);
        $document['created_at'] = $customer->created_at->toDateTime()->format('Y-m-d\Th:i');
        $document['updated_at'] = $customer->updated_at->toDateTime()->format('Y-m-d\Th:i');
        $document['interactions'] = [];

        foreach ($customer->interactions() as $interaction) {
            $document['interactions'][] = ElasticsearchCaster::castToEs($interaction);
        }

        return $document;
    }
}
