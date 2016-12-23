<?php
namespace Infrastructure\Search;

use Mongolid\Cursor\CursorInterface;
use Mongolid\Util\ObjectIdUtils;
use MongoDB\BSON\ObjectID;

/**
 * This class wraps the results of an Elasticsearch query in order to implement
 * the CursorInterface.
 */
class ElasticsearchCursorReadOnly extends ElasticsearchCursor
{
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
        $items = [];
        if (! isset($rawEsReturn['hits']['hits'][0]['_source'])) {
            return [];
        }

        foreach ($rawEsReturn['hits']['hits'] as $key => $hit) {
            if (isset($hit['_source'])) {
                $items[$key] = $hit['_source'];
                $items[$key]['_id'] = ObjectIdUtils::isObjectId($hit['_id']) ? new ObjectId($hit['_id']) : $hit['_id'];
            }
        }

        return $items;
    }
}
