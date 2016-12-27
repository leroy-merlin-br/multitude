<?php
namespace Leadgen\Customer\SegmentParsing;

/**
 * This SegmentParser step build the Elasticsearch query array of the given
 * segment.
 *
 * @see SegmentParser
 */
class StepEsQuery extends StepBase
{
    /**
     * Ruleset parser instance
     * @var ElasticsearchRulesetParser
     */
    protected $rulesetParser;

    /**
     * Injects the dependencies.
     *
     * @param ElasticsearchRulesetParser $rulesetParser Ruleset parser.
     */
    public function __construct(ElasticsearchRulesetParser $rulesetParser)
    {
        $this->rulesetParser = $rulesetParser;
    }

    /**
     * Process this step of the chain.
     *
     * @param  Dto $dto The input data.
     * @return Dto $dto after parsing.
     */
    protected function process(Dto $dto): Dto
    {
        if (! isset($dto->segment)) {
            return $dto;
        }

        $esQueryClauses = $this->rulesetParser->parse($dto->segment->ruleset());
        $esQueryClauses['_source'] = false;
        $esQueryClauses['size'] = 1000;
        $esQueryClauses['from'] = 0;

        $dto->esQueryClasues = $esQueryClauses;

        return $dto;
    }
}
