<?php
namespace Leadgen\Aggregation\InteractionParams;

use Leadgen\Interaction\Interaction;
use PHPUnit_Framework_TestCase;

class AggregatorTest extends PHPUnit_Framework_TestCase
{
    public function aggregateDataProvider()
    {
        $range = function ($start, $end) {
            $result = [];
            for ($i=$start; $i <= $end; $i++) {
                $result[] = $i;
            }
            return $result;
        };

        return [
            // --------------------
            'A bunch of interactions' => [
                '$interactions' => [
                    [
                        'params' => [
                            'foo' => 123,
                            'bar' => ['a', 'b', 'c'],
                        ]
                    ],
                    [
                        'params' => [
                            'foo' => 456,
                            'bar' => 'd',
                        ]
                    ],
                    [
                        'params' => [
                            'foo' => [123, 789, 101112],
                            'fuz' => 1.6,
                        ]
                    ],
                ],
                '$fields' => [
                    'foo',
                    'bar',
                    'fuz',
                ],
                '$expectation' => [
                    'foo' => [123, 456, 789, 101112],
                    'bar' => ['a', 'b', 'c', 'd'],
                    'fuz' => [1.6],
                ]
            ],

            // --------------------
            'Should not aggregate more than ENOUGH_VALUES const' => [
                '$interactions' => [
                    [
                        'params' => [
                            'foo' => $range(1,22),
                            'bar' => str_split('abc'),
                        ]
                    ],
                    [
                        'params' => [
                            'foo' => $range(22, 40),
                            'bar' => str_split('defghijklmnopqrstuvwxyzABCDEFGHIJKLMN'),
                        ]
                    ],
                    [
                        'params' => [
                            'foo' => $range(41, 50),
                            'bar' => 'Z',
                        ]
                    ],
                ],
                '$fields' => [
                    'foo',
                    'bar',
                ],
                '$expectation' => [
                    'foo' => $range(1,22),
                    'bar' => str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMN'),
                ]
            ],

            // --------------------
            'Should thrown an exception' => [
                '$interactions' => 'potato',
                '$fields' => [
                    'foo',
                    'bar',
                ],
                '$expectation' => [
                    'foo' => [],
                    'bar' => [],
                ],
                '$expectedException' => \InvalidArgumentException::class
            ],

            // --------------------
        ];
    }

    /**
     * @dataProvider aggregateDataProvider
     */
    public function testShouldAggregate($interactions, $fields, $expectation, $expectedException = null)
    {
        // Arrange
        if (is_array($interactions)) {
            foreach ($interactions as $key => $attributes) {
                $interactions[$key] = new Interaction;
                $interactions[$key]->fill($attributes, true);
            }
        }
        $aggregator = new Aggregator;

        // Act
        if ($expectedException) {
            $this->setExpectedException($expectedException);
        }

        // Assertion
        $this->assertEquals(
            $expectation,
            $aggregator->aggregate($interactions, $fields)
        );
    }
}
