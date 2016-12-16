<?php
namespace Leadgen\Segment;

use Leadgen\Customer\ElasticsearchQuery;

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
     * Injects dependencies
     * @param ElasticsearchQuery $customerEsQuery
     */
    public function __construct(ElasticsearchQuery $customerEsQuery)
    {
        $this->customerEsQuery = $customerEsQuery;
    }

    /**
     * Parse Ruleset objects into Elasticsearch queries in form of
     * associative arrays.
     *
     * @param  string $rules Rulesets object containing the rules
     *
     * @return array   Elasticsearch query (in form of an associative array)
     */
    public function preview($rules): array
    {
        // Set
        $parser = new ElasticsearchRulesetParser;
        $ruleset = new Ruleset;
        $ruleset->rules = $rules;

        return  $this->customerEsQuery->get($parser->parse($ruleset));
    }
}
