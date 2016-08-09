<?php
namespace Leadgen\InteractionType;

use Elasticsearch\Client;

/**
 * Updates the Mapping of an InteractionType in Elasticsearch
 */
class ElasticsearchMapper
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
     * Updates the mapping of the given InteractionType in ES.
     *
     * @param  InteractionType $eventType InteractionType being updated.
     *
     * @return boolean Success
     */
    public function map(InteractionType $eventType)
    {
        $indexName = app('config')->get('elasticsearch.defaultIndex', 'main');

        $mapping = [
            'index' => $indexName,
            'type' => 'Interaction',
            'body' => [
                'Interaction' => [
                    'properties' => array_merge(
                        $this->buildProperties($eventType),
                        [
                            'author' => [
                                'type' => 'string',
                                'index' => 'not_analyzed'
                            ],
                            'authorId' => [
                                'type' => 'string',
                                'index' => 'not_analyzed'
                            ],
                            'event' => [
                                'type' =>  'string',
                                'index' => 'not_analyzed'
                            ],
                            'created_at' => [
                                'type' => 'date',
                                'format' => 'date_hour_minute'
                            ],
                        ]
                    ),
                ]
            ]
        ];

        // Update the index mapping
        $result = $this->elasticsearch->indices()->putMapping($mapping);

        return $result['acknowledged'] ?? false;
    }

    /**
     * Build the properties of the given eventType to be mapped in elasticsearch
     *
     * @param  InteractionType $eventType That will have its properties parsed for es.
     *
     * @return array
     */
    protected function buildProperties(InteractionType $eventType)
    {
        $properties = [];

        foreach ($eventType->params() as $param) {
            $paramEsType = $param->type == 'string' ? 'string' : 'float';
            $properties['params/'.$param->name."/$paramEsType"] = [
                'type' => $paramEsType,
                'index' => 'not_analyzed'
            ];
        }

        return $properties;
    }
}
