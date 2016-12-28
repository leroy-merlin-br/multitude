<?php
namespace Leadgen\Customer\SegmentParsing;

use Infrastructure\Search\ElasticsearchQuery;
use Leadgen\Customer\Customer;

/**
 * This SegmentParser step runs the Elasticsearch query and return the ids of
 * the customers matched by the rule.
 *
 * @see SegmentParser
 */
class StepCustomerIds extends StepBase
{
    /**
     * Query instance that will be used to find out which customers match the
     * given segment.
     * @var ElasticsearchQuery
     */
    protected $customerEsQuery;

    /**
     * Injects the dependencies.
     *
     * @param ElasticsearchQuery $customerEsQuery Query instance.
     */
    public function __construct(ElasticsearchQuery $customerEsQuery)
    {
        $this->customerEsQuery = $customerEsQuery;
    }

    /**
     * Process this step of the chain.
     *
     * @param  Dto $dto The input data.
     * @return Dto $dto after parsing.
     */
    protected function process(Dto $dto): Dto
    {
        if (! isset($dto->esQueryClauses)) {
            return $dto;
        }

        $dto->customerIds = $this->getCustomerIds($dto->esQueryClauses);

        return $dto;
    }

    /**
     * Get the Ids of all customers that are matched by the given query
     *
     * @param array $esQuery Elasticsearch query to be executed.
     *
     * @return array Array with all the _ids of Customers that were matched by the $esQuery.
     */
    protected function getCustomerIds(array $esQuery): array
    {
        // Set
        $idOfHits = [];
        $remaining = 1;

         // Loops in a way that it will make additional queries in order to
        // get all _ids of maching Customers.
        while ($remaining > 0) {
            $cursor = $this->customerEsQuery->get(
                $esQuery,
                Customer::class
            );

            $idOfHits = array_merge($idOfHits, $cursor->getIdOfHits());
            $esQuery['from'] += $esQuery['size'];
            $remaining = $cursor->countPossible() - $esQuery['from'];
        }

        return $idOfHits;
    }
}
