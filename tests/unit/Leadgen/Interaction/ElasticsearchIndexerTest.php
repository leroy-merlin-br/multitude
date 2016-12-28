<?php

namespace Leadgen\Interaction;

use Elasticsearch\Client;
use Mockery as m;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use PHPUnit_Framework_TestCase;

/**
 * Index `Interaction`s into Elasticsearch.
 */
class ElasticsearchIndexerTest extends PHPUnit_Framework_TestCase
{
    public function bulkIndexDataProvider()
    {
        $interactionA = new Interaction();
        $interactionA->fill([
            '_id'    => new ObjectId('507f1f77bcf86cd799439011'),
            'params' => [
                'something'  => 'somevalue',
                'somenumber' => 2.3,
            ],
            'created_at' => new UTCDateTime(new \DateTime('2016-12-17')),
            'updated_at' => new UTCDateTime(new \DateTime('2016-12-18')),
        ]);

        $interactionB = new Interaction();
        $interactionB->fill([
            '_id'    => new ObjectId('507f191e810c19729de860ea'),
            'params' => [
                'something'  => 'somevalue',
                'somenumber' => 2.3,
            ],
            'created_at' => new UTCDateTime(new \DateTime('2016-12-17')),
            'updated_at' => new UTCDateTime(new \DateTime('2016-12-18')),
        ]);

        return [
            // ---------------
            'single interaction' => [
                '$interactions' => [
                    $interactionA,
                ],
                '$indexExpectation' => [
                    'body' => [
                        [
                            'index' => [
                                '_index' => 'leadgen_test',
                                '_type'  => 'Interaction',
                                '_id'    => '507f1f77bcf86cd799439011',
                            ],
                        ],
                        [
                            'params/something/string'  => 'somevalue',
                            'params/somenumber/float'  => 2.3,
                            'params/somenumber/string' => 2.3,
                            'created_at' => '2016-12-17T12:00',
                            'updated_at' => '2016-12-18T12:00',
                        ],
                    ],
                ],
                '$indexResponse' => [
                    'items' => [
                        [
                            'index' => [
                                'status' => 201,
                                '_id'    => '507f1f77bcf86cd799439011',
                            ],
                        ],
                    ],
                ],
                '$outputExpectation' => [
                    new ObjectID('507f1f77bcf86cd799439011'),
                ],

            ],

            // ---------------
            'multiple interactions' => [
                '$interactions' => [
                    $interactionA,
                    $interactionB,
                ],
                '$indexExpectation' => [
                    'body' => [
                        [
                            'index' => [
                                '_index' => 'leadgen_test',
                                '_type'  => 'Interaction',
                                '_id'    => '507f1f77bcf86cd799439011',
                            ],
                        ],
                        [
                            'params/something/string'  => 'somevalue',
                            'params/somenumber/float'  => 2.3,
                            'params/somenumber/string' => 2.3,
                            'created_at' => '2016-12-17T12:00',
                            'updated_at' => '2016-12-18T12:00',
                        ],
                        [
                            'index' => [
                                '_index' => 'leadgen_test',
                                '_type'  => 'Interaction',
                                '_id'    => '507f191e810c19729de860ea',
                            ],
                        ],
                        [
                            'params/something/string'  => 'somevalue',
                            'params/somenumber/float'  => 2.3,
                            'params/somenumber/string' => 2.3,
                            'created_at' => '2016-12-17T12:00',
                            'updated_at' => '2016-12-18T12:00',
                        ],
                    ],
                ],
                '$indexResponse' => [
                    'items' => [
                        [
                            'index' => [
                                'status' => 201,
                                '_id'    => '507f191e810c19729de860ea',
                            ],
                        ],
                    ],
                ],
                '$outputExpectation' => [
                    new ObjectID('507f191e810c19729de860ea'),
                ],
            ],

            // ---------------
        ];
    }

    /**
     * @dataProvider bulkIndexDataProvider
     */
    public function testShouldIndexInteractions($interactions, $indexExpectation, $indexResponse, $outputExpectation)
    {
        // Arrange
        $elasticsearch = m::mock(Client::class);
        $test = $this;
        $esIndexer = new ElasticsearchIndexer($elasticsearch);

        // Act
        $elasticsearch->shouldReceive('bulk')
            ->andReturnUsing(function ($params) use ($test, $indexExpectation, $indexResponse) {
                $this->assertEquals($indexExpectation, $params);

                return $indexResponse;
            });

        // Assert
        $this->assertEquals(
            $outputExpectation,
            $esIndexer->index($interactions)
        );
    }
}
