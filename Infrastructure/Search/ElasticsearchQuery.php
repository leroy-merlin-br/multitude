<?php
namespace Infrastructure\Search;

use Elasticsearch\Client;
use Infrastructure\Search\ElasticsearchCursor;

/**
 * Perform queries into Elasticsearch.
 */
class ElasticsearchQuery
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
     * Retrieves customers from Elasticsearch matching the given query.
     *
     * @param array   $query       Elasticsearch query body.
     * @param string  $entityClass Class of the objects that will be retrieved by the cursor.
     * @param boolean $readOnly    Creates the entities from the _source of elasticsearch (don't touch the primary database). Use false if the entities from the resulting cursor will be modified.
     *
     * @return ElasticsearchCursor results
     */
    public function get(array $query, string $entityClass, bool $readOnly = true): ElasticsearchCursor
    {
        $indexName = app('config')->get('elasticsearch.defaultIndex', 'main');

        $params = [
            'index' => $indexName,
            'type'  => 'Customer',
            'body'  => $query,
        ];

        $esResult = $this->elasticsearch->search($params);

        if ($readOnly) {
            return new ElasticsearchCursorReadOnly($entityClass, $esResult);
        }

        return new ElasticsearchCursor($entityClass, $esResult);
    }
}
