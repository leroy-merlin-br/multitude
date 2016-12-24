<?php

namespace Leadgen\InteractionType;

use Elasticsearch\Client;
use Mockery as m;
use MongoDB\BSON\ObjectID;
use PHPUnit_Framework_TestCase;

class ElasticsearchMapperTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testShouldHaveAnAliasToMapInteractionsAndCustomers()
    {
        // Arrange
        $elasticsearch = m::mock(Client::class);
        $esMapper = m::mock(ElasticsearchMapper::class.'[mapInteractions,mapCustomers]', [$elasticsearch]);
        $interactionType = $this->sampleInteractionType();

        // Act
        $esMapper->shouldReceive('mapInteractions')
            ->once()
            ->with($interactionType)
            ->andReturn(true);

        $esMapper->shouldReceive('mapCustomers')
            ->once()
            ->with($interactionType)
            ->andReturn(true);

        // Assertion
        $this->assertTrue($esMapper->map($interactionType));
    }

    public function testShouldMapInteractions()
    {
        // Arrange
        $elasticsearch = m::mock(Client::class);
        $esMapper = new ElasticsearchMapper($elasticsearch);
        $interactionType = $this->sampleInteractionType();

        $expectedMapping = [
            'index' => 'leadgen',
            'type'  => 'Interaction',
            'body'  => [
                'Interaction' => [
                    'properties' => [
                        'params/product-id/float' => [
                            'type'  => 'float',
                            'index' => 'not_analyzed',
                        ],
                        'params/total/float' => [
                            'type'  => 'float',
                            'index' => 'not_analyzed',
                        ],
                        'params/details/string' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'author' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'authorId' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'interaction' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'channel' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'created_at' => [
                            'type'   => 'date',
                            'format' => 'date_hour_minute',
                        ],
                    ],
                ],
            ],
        ];

        // Act
        $elasticsearch->shouldReceive('indices')
            ->andReturn($elasticsearch);

        $elasticsearch->shouldReceive('putMapping')
            ->once()
            ->andReturnUsing(function ($mapping) use ($expectedMapping) {
                $this->assertEquals($expectedMapping, $mapping);

                return ['acknowledged' => true];
            });

        // Assert
        $this->assertTrue($esMapper->mapInteractions($interactionType));
    }

    public function testShouldMapCustomers()
    {
        // Arrange
        $elasticsearch = m::mock(Client::class);
        $esMapper = new ElasticsearchMapper($elasticsearch);
        $interactionType = $this->sampleInteractionType();

        $expectedMapping = [
            'index' => 'leadgen',
            'type'  => 'Customer',
            'body'  => [
                'Customer' => [
                    'dynamic' => false,
                    'properties' => [
                        'docNumber' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'email' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'name' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'location' => [
                            'type'  => 'string',
                            'index' => 'not_analyzed',
                        ],
                        'interactions' => [
                            'type'       => 'nested',
                            'properties' => [
                                'params/product-id/float' => [
                                    'type'  => 'float',
                                    'index' => 'not_analyzed',
                                ],
                                'params/total/float' => [
                                    'type'  => 'float',
                                    'index' => 'not_analyzed',
                                ],
                                'params/details/string' => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                                'interaction' => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                                'channel' => [
                                    'type'  => 'string',
                                    'index' => 'not_analyzed',
                                ],
                                'created_at' => [
                                    'type'   => 'date',
                                    'format' => 'date_hour_minute',
                                ],
                            ],
                        ],
                        'created_at' => [
                            'type'   => 'date',
                            'format' => 'date_hour_minute',
                        ],
                        'updated_at' => [
                            'type'   => 'date',
                            'format' => 'date_hour_minute',
                        ],
                    ],
                ],
            ],
        ];

        // Act
        $elasticsearch->shouldReceive('indices')
            ->andReturn($elasticsearch);

        $elasticsearch->shouldReceive('putMapping')
            ->once()
            ->andReturnUsing(function ($mapping) use ($expectedMapping) {
                $this->assertEquals($expectedMapping, $mapping);

                return ['acknowledged' => true];
            });

        // Assert
        $this->assertTrue($esMapper->mapCustomers($interactionType));
    }

    protected function sampleInteractionType()
    {
        $interactionType = new InteractionType();

        $interactionType->fill([
            '_id'    => new ObjectID('57aa822f0374211d65333958'),
            'name'   => 'Purchased products',
            'slug'   => 'purchased-products',
            'params' => [
                [
                    'name'     => 'product-id',
                    'type'     => 'numeric',
                    'required' => true,
                ],
                [
                    'name'     => 'total',
                    'type'     => 'numeric',
                    'required' => true,
                ],
                [
                    'name'     => 'details',
                    'type'     => 'string',
                    'required' => false,
                ],
            ],
        ]);

        return $interactionType;
    }
}
