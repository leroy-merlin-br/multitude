<?php

namespace Leadgen\Customer;

use Elasticsearch\Client;
use Leadgen\Interaction\Interaction;
use Mockery as m;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use PHPUnit_Framework_TestCase;

/**
 * Index `Customer`s into Elasticsearch.
 */
class ElasticsearchIndexerTest extends PHPUnit_Framework_TestCase
{
    public function bulkIndexDataProvider()
    {
        $interaction = new Interaction();
        $interaction->fill([
            '_id'    => new ObjectId('507f1f77bcf86cd799439011'),
            'params' => [
                'something'  => 'somevalue',
                'somenumber' => 2.3,
            ],
            'created_at' => new UTCDateTime(new \DateTime('2016-12-17')),
            'updated_at' => new UTCDateTime(new \DateTime('2016-12-18')),
        ]);

        $customerA = new Customer();
        $customerA->fill([
            '_id'          => new ObjectId('507f1f77bcf86cd799439022'),
            'interactions' => [$interaction],
            'created_at'   => new UTCDateTime(new \DateTime('2016-12-17')),
            'updated_at'   => new UTCDateTime(new \DateTime('2016-12-18')),
        ]);

        $customerB = new Customer();
        $customerB->fill([
            '_id'          => new ObjectId('507f191e810c19729de860ab'),
            'interactions' => [$interaction],
            'created_at'   => new UTCDateTime(new \DateTime('2016-12-17')),
            'updated_at'   => new UTCDateTime(new \DateTime('2016-12-18')),
        ]);

        return [
            // ---------------
            'single customer' => [
                '$customers' => [
                    $customerA,
                ],
                '$indexExpectation' => [
                    'body' => [
                        [
                            'index' => [
                                '_index' => 'leadgen_test',
                                '_type'  => 'Customer',
                                '_id'    => '507f1f77bcf86cd799439022',
                            ],
                        ],
                        [
                            'interactions' => [
                                [
                                    'params/something/string'  => 'somevalue',
                                    'params/somenumber/float'  => 2.3,
                                    'params/somenumber/string' => 2.3,
                                    'created_at' => '2016-12-17T12:00',
                                    'updated_at' => '2016-12-18T12:00',
                                ],
                            ],
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
                                '_id'    => '507f1f77bcf86cd799439022',
                            ],
                        ],
                    ],
                ],
                '$outputExpectation' => [
                    new ObjectID('507f1f77bcf86cd799439022'),
                ],

            ],

            // ---------------
            'multiple customers' => [
                '$customers' => [
                    $customerA,
                    $customerB,
                ],
                '$indexExpectation' => [
                    'body' => [
                        [
                            'index' => [
                                '_index' => 'leadgen_test',
                                '_type'  => 'Customer',
                                '_id'    => '507f1f77bcf86cd799439022',
                            ],
                        ],
                        [
                            'interactions' => [
                                [
                                    'params/something/string'  => 'somevalue',
                                    'params/somenumber/float'  => 2.3,
                                    'params/somenumber/string' => 2.3,
                                    'created_at' => '2016-12-17T12:00',
                                    'updated_at' => '2016-12-18T12:00',
                                ],
                            ],
                            'created_at' => '2016-12-17T12:00',
                            'updated_at' => '2016-12-18T12:00',
                        ],
                        [
                            'index' => [
                                '_index' => 'leadgen_test',
                                '_type'  => 'Customer',
                                '_id'    => '507f191e810c19729de860ab',
                            ],
                        ],
                        [
                            'interactions' => [
                                [
                                    'params/something/string'  => 'somevalue',
                                    'params/somenumber/float'  => 2.3,
                                    'params/somenumber/string' => 2.3,
                                    'created_at' => '2016-12-17T12:00',
                                    'updated_at' => '2016-12-18T12:00',
                                ],
                            ],
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
                                '_id'    => '507f191e810c19729de860ab',
                            ],
                        ],
                    ],
                ],
                '$outputExpectation' => [
                    new ObjectID('507f191e810c19729de860ab'),
                ],
            ],

            // ---------------
        ];
    }

    /**
     * @dataProvider bulkIndexDataProvider
     */
    public function testShouldIndexCustomers($customers, $indexExpectation, $indexResponse, $outputExpectation)
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
            $esIndexer->index($customers)
        );
    }
}
