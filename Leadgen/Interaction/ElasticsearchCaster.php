<?php

namespace Leadgen\Interaction;

class ElasticsearchCaster
{
    /**
     * Cast an Interaction object to an associative array ready to be indexed
     * in Elasticsearch.
     *
     * @param Interaction $interaction Interaction to be casted.
     *
     * @return array Resulting associative array of Interaction.
     */
    public static function castToEs(Interaction $interaction)
    {
        $document = array_diff_key($interaction->attributes, ['_id' => 1, 'params' => 1]);

        foreach (['created_at', 'updated_at'] as $dateField) {
            $document[$dateField] = $interaction
                ->$dateField
                ->toDateTime()
                ->format('Y-m-d\Th:i');
        }

        $document['params'] = [];

        foreach ($interaction->params as $key => $value) {
            if (is_numeric($value)) {
                $document['params']["params/$key/float"] = $value;
            }
            $document['params']["params/$key/string"] = $value;
        }

        return $document;
    }
}
