<?php
namespace Leadgen\Segment;

/**
 * A service class (has no state) that aims to parse Ruleset objects into
 * Elasticsearch queries in form of associative arrays.
 */
class ElasticsearchRulesetParser
{
    /**
     * Parse Ruleset objects into Elasticsearch queries in form of
     * associative arrays.
     *
     * @param  Ruleset $ruleset Rulesets object containing the rules
     *
     * @return array   Elasticsearch query (in form of an associative array)
     */
    public function parse(Ruleset $ruleset): array
    {
        if (empty($ruleset->rules)) {
            return $this->matchAllQuery();
        }

        return $this->baseQueryBody(
            $this->prepareFilterQuery($ruleset->rules)
        );
    }

    protected function matchAllQuery(): array
    {
        return [
            'query' => [
                'constant_score' => [
                    'filter' => [
                        'match_all' => []
                    ]
                ]
            ]
        ];
    }

    protected function prepareFilterQuery($rules): array
    {
        $condition = strtolower($rules['condition'] ?: 'and');
        $output = [$condition => []];

        foreach ($rules['rules'] ?: [] as $subrule) {
            if (isset($subrule['rules'])) {
                $subruleObj = [
                    'nested' => [
                        'path' => 'interactions',
                        'query' => [
                            'constant_score' => [
                                'filter' => $this->prepareFilterQuery($subrule)
                            ]
                        ]
                    ]
                ];
            } else if ($subrule['operator'] == 'equal') {
                $subruleObj = [
                    'match' => [
                        "interactions.params.params/{$subrule['field']}/{$subrule['type']}" => $subrule['value']
                    ]
                ];
            } else if ($subrule['operator'] == 'in') {
                $subruleObj = [
                    'terms' => [
                        "interactions.params.params/{$subrule['field']}/{$subrule['type']}" => $subrule['value']
                    ]
                ];
            } else if ($subrule['operator'] == 'greater_or_equal') {
                $subruleObj = [
                    'range' => [
                        "interactions.params.params/{$subrule['field']}/{$subrule['type']}" => [
                            'gte' => (float) $subrule['value']
                        ]
                    ]
                ];
            } else if ($subrule['operator'] == 'less_or_equal') {
                $subruleObj = [
                    'range' => [
                        "interactions.params.params/{$subrule['field']}/{$subrule['type']}" => [
                            'lte' => (float) $subrule['value']
                        ]
                    ]
                ];
            }

            if (!isset($subruleObj)) {
                dd($subrule);
            }
            $output[$condition][] = $subruleObj;
        }

        return $output;
    }

    /**
     * Return the base query body, the squeleton of the query that mostly don't
     * change.
     *
     * @return array BaseQuery
     */
    protected function baseQueryBody($filterQuery):array {
        return [
            'query' => [
                'constant_score' => [
                    'filter' => $filterQuery
                ]
            ]
        ];
    }
}
