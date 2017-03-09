<?php
namespace Infrastructure\Search;

use Mongolid\Cursor\CursorInterface;
use Mongolid\Cursor\EmbeddedCursor;
use Mongolid\Util\ObjectIdUtils;
use MongoDB\BSON\ObjectID;

/**
 * This class wraps the results of an Elasticsearch query in order to implement
 * the CursorInterface.
 */
class ElasticsearchCursor extends EmbeddedCursor implements CursorInterface
{
    /**
     * The actual return from elasticsearch
     *
     * @var array
     */
    protected $rawEsReturn = [];

    /**
     * @param string $entityClass Class of the objects that will be retrieved by the cursor.
     * @param array  $rawEsReturn The return from Elasticsearch.
     */
    public function __construct(string $entityClass, array $rawEsReturn)
    {
        $this->rawEsReturn = $rawEsReturn;
        $this->entityClass = $entityClass;
        $this->items = $this->getItemsFromResult($rawEsReturn);
    }

    /**
     * Returns elasticsearch `hits.total`, which tells the total count of
     * documents that were matched by the query.
     *
     * @return integer
     */
    public function countPossible(): int
    {
        return array_get($this->rawEsReturn, 'hits.total', 0);
    }

    /**
     * The actual return from elasticsearch.
     *
     * @return array
     */
    public function getRaw(): array
    {
        return $this->rawEsReturn;
    }

    /**
     * Parses the raw elasticsearch return to extract the items to compose an
     * the cursor.
     *
     * @param array $rawEsReturn Elasticsearch raw return.
     *
     * @return array
     */
    protected function getItemsFromResult(array $rawEsReturn): array
    {
        if (! isset($rawEsReturn['hits']['hits'][0]['_source'])) {
            return [];
        }

        return $this->entityClass::where(['_id' => [
                '$in' => $this->getIdOfHits()
            ]])->all();
    }

    /**
     * Return the _id field of the hits
     *
     * @return array
     */
    public function getIdOfHits(): array
    {
        $ids = [];

        foreach ($this->rawEsReturn['hits']['hits'] as $hit) {
            if (isset($hit['_id'])) {
                $ids[] = ObjectIdUtils::isObjectId($hit['_id']) ? new ObjectId($hit['_id']) : $hit['_id'];
            }
        }

        return $ids;
    }
}
