<?php

namespace Leadgen\Segment;

use Infrastructure\Search\ElasticsearchQuery;
use Leadgen\Customer\Customer;
use Mongolid\Cursor\CursorInterface;

/**
 * A service class (has no state) that aims to test the given rules array, in
 * Ruleset format, to learn which customers are matched by those rules.
 */
class RulesetPreviewService
{
    /**
     * @var \Leadgen\Customer\ElasticsearchQuery
     */
    protected $customerEsQuery;

    /**
     * Injects dependencies.
     *
     * @param ElasticsearchQuery $customerEsQuery Query instance.
     */
    public function __construct(ElasticsearchQuery $customerEsQuery)
    {
        $this->customerEsQuery = $customerEsQuery;
    }

    /**
     * Parse Ruleset objects into Elasticsearch queries in form of
     * associative arrays.
     *
     * @param array $rules Rulesets object containing the rules.
     *
     * @return CursorInterface Elasticsearch query result
     */
    public function preview(array $rules): CursorInterface
    {
        // Set
        $parser = new ElasticsearchRulesetParser();
        $ruleset = new Ruleset();
        $ruleset->rules = $rules;

        return  $this->customerEsQuery->get(
            $parser->parse($ruleset),
            Customer::class,
            false
        );
    }
}
