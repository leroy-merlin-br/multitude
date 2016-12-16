<?php
namespace Leadgen\Segment;

use PHPUnit_Framework_TestCase;

class ElasticsearchRulesetParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Leadgen\Segment\ElasticsearchRulesetParser
     * @dataProvider inAndOutProvider
     */
    public function testShouldParseRulesetIntoElasticsearchQueries($in, $expectedOut)
    {
        // Set
        $parser = new ElasticsearchRulesetParser;
        $ruleset = new Ruleset;
        $ruleset->rules = $in;

        // Assert
        $this->assertEquals($expectedOut, $parser->parse($ruleset));
    }

    public function inAndOutProvider(): array
    {
        return [
            // --------------------
            'empty ruleset' => [
                '$in' => [],
                '$out' => [
                    'query' => [
                        'constant_score' => [
                            'filter' => [
                                'match_all' => []
                            ]
                        ]
                    ]
                ]
            ],

            // --------------------
            'simple two interactions match' => [
                '$in' => [
                    "condition" => "AND",
                    "rules" => [
                        [
                            "condition" => "AND",
                            "rules" => [
                                [
                                    "id" => "category",
                                    "field" => "category",
                                    "type" => "string",
                                    "input" => "text",
                                    "operator" => "equal",
                                    "value" => "banheiros"
                                ]
                            ]
                        ],
                        [
                            "condition" => "AND",
                            "rules" => [
                                [
                                    "id" => "productId",
                                    "field" => "productId",
                                    "type" => "string",
                                    "input" => "text",
                                    "operator" => "equal",
                                    "value" => "88880123"
                                ]
                            ]
                        ],
                    ]
                ],
                '$out' => [
                    'query' => [
                        'constant_score' => [
                            'filter' => [
                                'and' => [
                                    [
                                        'nested' => [
                                            'path' => 'interactions',
                                            'query' => [
                                                'constant_score' => [
                                                    'filter' => [
                                                        'and' => [
                                                            [
                                                                'match' => [
                                                                    'interactions.params.params/category/string' => 'banheiros',
                                                                ]
                                                            ],
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        'nested' => [
                                            'path' => 'interactions',
                                            'query' => [
                                                'constant_score' => [
                                                    'filter' => [
                                                        'and' => [
                                                            [
                                                                'match' => [
                                                                    'interactions.params.params/productId/string' => '88880123',
                                                                ]
                                                            ],
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ],

            // --------------------
            'two interactions match with or clause' => [
                '$in' => [
                    "condition" => "OR",
                    "rules" => [
                        [
                            "condition" => "AND",
                            "rules" => [
                                [
                                    "id" => "category",
                                    "field" => "category",
                                    "type" => "string",
                                    "input" => "text",
                                    "operator" => "equal",
                                    "value" => "banheiros"
                                ]
                            ]
                        ],
                        [
                            "condition" => "AND",
                            "rules" => [
                                [
                                    "id" => "productId",
                                    "field" => "productId",
                                    "type" => "string",
                                    "input" => "text",
                                    "operator" => "equal",
                                    "value" => "88880123"
                                ]
                            ]
                        ],
                    ]
                ],
                '$out' => [
                    'query' => [
                        'constant_score' => [
                            'filter' => [
                                'or' => [
                                    [
                                        'nested' => [
                                            'path' => 'interactions',
                                            'query' => [
                                                'constant_score' => [
                                                    'filter' => [
                                                        'and' => [
                                                            [
                                                                'match' => [
                                                                    'interactions.params.params/category/string' => 'banheiros',
                                                                ]
                                                            ],
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    [
                                        'nested' => [
                                            'path' => 'interactions',
                                            'query' => [
                                                'constant_score' => [
                                                    'filter' => [
                                                        'and' => [
                                                            [
                                                                'match' => [
                                                                    'interactions.params.params/productId/string' => '88880123',
                                                                ]
                                                            ],
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ],

            // --------------------
            'numeric range and \'or\' in subcondition' => [
                '$in' => [
                    "condition" => "AND",
                    "rules" => [
                        [
                            "condition" => "OR",
                            "rules" => [
                                [
                                    "id" => "interaction",
                                    "field" => "interaction",
                                    "type" => "string",
                                    "input" => "checkbox",
                                    "operator" => "in",
                                    "value" => [
                                        "added-to-basket"
                                    ]
                                ],
                                [
                                    "id" => "price",
                                    "field" => "price",
                                    "type" => "double",
                                    "input" => "text",
                                    "operator" => "greater_or_equal",
                                    "value" => "190"
                                ],
                                [
                                    "id" => "price",
                                    "field" => "price",
                                    "type" => "double",
                                    "input" => "text",
                                    "operator" => "less_or_equal",
                                    "value" => "300"
                                ]
                            ]
                        ]
                    ]
                ],
                '$out' => [
                    'query' => [
                        'constant_score' => [
                            'filter' => [
                                'and' => [
                                    [
                                        'nested' => [
                                            'path' => 'interactions',
                                            'query' => [
                                                'constant_score' => [
                                                    'filter' => [
                                                        'or' => [
                                                            [
                                                                'terms' => [
                                                                    'interactions.params.params/interaction/string' => ["added-to-basket"],
                                                                ]
                                                            ],
                                                            [
                                                                'range' => [
                                                                    'interactions.params.params/price/double' => ['gte' => 190],
                                                                ]
                                                            ],
                                                            [
                                                                'range' => [
                                                                    'interactions.params.params/price/double' => ['lte' => 300],
                                                                ]
                                                            ],
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ],

            // --------------------
            'date range in interaction' => [
                '$in' => [
                    "condition" => "AND",
                    "rules" => [
                        [
                            "condition" => "AND",
                            "rules" => [
                                [
                                    "id" => "term",
                                    "field" => "term",
                                    "type" => "string",
                                    "input" => "text",
                                    "operator" => "equal",
                                    "value" => "Quality potatoes"
                                ],
                                [
                                    "id" => "created_at-h",
                                    "field" => "created_at-h",
                                    "type" => "integer",
                                    "input" => "text",
                                    "operator" => "greater_or_equal",
                                    "value" => "48"
                                ],
                                [
                                    "id" => "created_at-m",
                                    "field" => "created_at-m",
                                    "type" => "integer",
                                    "input" => "text",
                                    "operator" => "less_or_equal",
                                    "value" => "6"
                                ],
                            ]
                        ]
                    ]
                ],
                '$out' => [
                    'query' => [
                        'constant_score' => [
                            'filter' => [
                                'and' => [
                                    [
                                        'nested' => [
                                            'path' => 'interactions',
                                            'query' => [
                                                'constant_score' => [
                                                    'filter' => [
                                                        'and' => [
                                                            [
                                                                'match' => [
                                                                    'interactions.params.params/term/string' => 'Quality potatoes',
                                                                ]
                                                            ],
                                                            [
                                                                'range' => [
                                                                    'interactions.created_at' => ['gte' => "now-48h/h"],
                                                                ]
                                                            ],
                                                            [
                                                                'range' => [
                                                                    'interactions.created_at' => ['lte' => "now-6m/m"],
                                                                ]
                                                            ],
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}
