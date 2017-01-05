<?php
namespace Leadgen\ExactTarget;

use Leadgen\Customer\Customer;
use LeroyMerlin\ExactTarget\Client;
use LeroyMerlin\ExactTarget\Exception\ExactTargetClientException;
use Mockery as m;
use MongoDB\BSON\UTCDateTime;
use Mongolid\Cursor\Cursor;
use Mongolid\Serializer\Type\UTCDateTime as MongolidUTCDateTime;
use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;

class CustomerUpdaterTest extends PHPUnit_Framework_TestCase
{
    public function sendMethodDataProvider()
    {
        return [
            // ------------------------------
            'empty customers' => [
                '$customers' => [],
                '$dataExtension' => 'fooBar',
                '$fields' => [],
                '$expectations' => [
                    'key' => 'fooBar',
                    'data' => []
                ]
            ],

            // ------------------------------
            'some customers' => [
                '$customers' => [
                    [
                        'email' => 'johndoe@gmail.com',
                        'docNumber' => '1234567'
                    ],
                    [
                        'email' => 'example@example.com',
                        'docNumber' => '456789'
                    ],
                    [
                        'email' => 'random@customer.com'
                    ]
                ],
                '$dataExtension' => 'some-thing',
                '$fields' => [],
                '$expectations' => [
                    'key' => 'some-thing',
                    'data' => [
                        [
                            'keys' => ['Email' => 'johndoe@gmail.com'],
                            'values' => ['Email' => 'johndoe@gmail.com']
                        ],
                        [
                            'keys' => ['Email' => 'example@example.com'],
                            'values' => ['Email' => 'example@example.com']
                        ],
                        [
                            'keys' => ['Email' => 'random@customer.com'],
                            'values' => ['Email' => 'random@customer.com']
                        ],
                    ]
                ]
            ],

            'customers with custom fields' => [
                '$customers' => [
                    [
                        'email' => 'johndoe@gmail.com',
                        'aggregated' => [
                            'stuff' => [1, 2, 3],
                            'moreStuff' => ['a', 'b']
                        ],
                        'created_at' => new UTCDateTime(new \DateTime('2017-01-05'))
                    ],
                    [
                        'email' => 'example@example.com',
                        'aggregated' => [
                            'stuff' => [4, 5, 6]
                        ]
                    ],
                ],
                '$dataExtension' => 'some-thing',
                '$fields' => [
                    'aggregated/stuff' => 'Stuff',
                    'aggregated/moreStuff' => 'MoreStuff',
                    'created_at' => 'CreatedAt'
                ],
                '$expectations' => [
                    'key' => 'some-thing',
                    'data' => [
                        [
                            'keys' => ['Email' => 'johndoe@gmail.com'],
                            'values' => [
                                'Email' => 'johndoe@gmail.com',
                                'Stuff' => '1;2;3',
                                'MoreStuff' => 'a;b',
                                'CreatedAt' => '2017-01-05T00:00:00+00:00'
                            ]
                        ],
                        [
                            'keys' => ['Email' => 'example@example.com'],
                            'values' => [
                                'Email' => 'example@example.com',
                                'Stuff' => '4;5;6'
                            ]
                        ]
                    ]
                ]
            ],

            // ------------------------------
            'critical error' => [
                '$customers' => [
                    ['email' => 'johndoe@gmail.com'],
                    ['email' => 'random@customer.com']
                ],
                '$dataExtension' => 'some-thing',
                '$fields' => [],
                '$expectations' => [
                    'key' => 'some-thing',
                    'data' => [
                        [
                            'keys' => ['Email' => 'johndoe@gmail.com'],
                            'values' => ['Email' => 'johndoe@gmail.com']
                        ],
                        [
                            'keys' => ['Email' => 'random@customer.com'],
                            'values' => ['Email' => 'random@customer.com']
                        ],
                    ]
                ],
                '$expectedResult' => false,
                '$error' => new ExactTargetClientException('Critical error!')
            ],

            // ------------------------------
            'non critical error' => [
                '$customers' => [
                    ['email' => 'johndoe@gmail.com'],
                    ['email' => 'random@customer.com']
                ],
                '$dataExtension' => 'some-thing-else',
                '$fields' => [],
                '$expectations' => [
                    'key' => 'some-thing-else',
                    'data' => [
                        [
                            'keys' => ['Email' => 'johndoe@gmail.com'],
                            'values' => ['Email' => 'johndoe@gmail.com']
                        ],
                        [
                            'keys' => ['Email' => 'random@customer.com'],
                            'values' => ['Email' => 'random@customer.com']
                        ],
                    ]
                ],
                '$expectedResult' => true,
                '$error' => new ExactTargetClientException('InvalidEmailAddress: random@customer.com')
            ],

            // ------------------------------
        ];
    }

    /**
     * @dataProvider sendMethodDataProvider
     */
    public function testShouldSendCustomersAndRecoverFromErrors(
        $customers,
        $dataExtension,
        $fields,
        $expectations,
        $expectedResult = true,
        $error = null
    ) {
        // Arrange
        $exacttarget     = m::mock(Client::class);
        $logger          = m::mock(LoggerInterface::class);
        $customerUpdater = new CustomerUpdater($exacttarget, $logger);
        $test            = $this;

        foreach ($customers as $key => $value) {
            $customers[$key] = new Customer;
            $customers[$key]->fill($value, true);
        }

        // Act
        $exacttarget->shouldReceive('addDataExtensionRow')
            ->once()
            ->andReturnUsing(function ($parameters) use ($test, $expectations, $error, $logger) {
                $test->assertEquals($expectations, $parameters);
                if ($error) {
                    throw $error;
                }
            });

        if ($error) {
            $logger->shouldReceive('error')
                ->once()
                ->andReturnUsing(function ($message) use ($test, $error) {
                    $this->assertTrue(true && strstr($message, $error->getMessage()));
                });
        }

        $logger->shouldReceive('info');

        // Assert
        $this->assertEquals(
            $expectedResult,
            $customerUpdater->send($customers, $dataExtension, $fields)
        );
    }

    public function testShouldThrownExceptionIfCustomersAreNotValid()
    {
        // Arrange
        $exacttarget     = m::mock(Client::class);
        $logger          = m::mock(LoggerInterface::class);
        $customerUpdater = new CustomerUpdater($exacttarget, $logger);
        $customers       = 7; // An integer. lol

        // Act
        $this->setExpectedException(\InvalidArgumentException::class);

        // Assert
        $customerUpdater->send($customers, 'fooBar');
    }
}
