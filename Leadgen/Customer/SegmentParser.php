<?php
namespace Leadgen\Customer;

use Infrastructure\Search\ElasticsearchQuery;
use Leadgen\Customer\Customer;
use Leadgen\Segment\ElasticsearchRulesetParser;
use Leadgen\Segment\Segment;
use MongoDB\Driver\WriteConcern;

/**
 * A service that, by receiving a segment, will try to find the Customers that
 * match it and add a reference of the segment into then.
 */
class SegmentParser
{
    /**
     * The segment that is being parsed
     * @var Segment
     */
    protected $segment;

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
     * Find out which users matchs the given segment.
     *
     * @param Segment $segment The segment that is being parsed.
     *
     * @return int Number of affected documents.
     */
    public function parse(Segment $segment): int
    {
        $this->segment = $segment;
        return $this->callUpdate($this->getCustomerIds());
    }

    /**
     * Call updateMany in mongodb in order to add the segment to the Customer
     * documents
     *
     * @param  array $customerIds Array containing the _ids of the customers.
     *
     * @return int Number of affected documents.
     */
    protected function callUpdate(array $customerIds): int
    {
        return (new Customer)->collection()->updateMany(
            [
                '_id' => [
                    '$in' => $customerIds,
                ],
                'segments' => [
                    '$exists' => true,
                    '$gte' => [] // Checks if type is array
                ]
            ],
            [
                '$addToSet' => [
                    'segments' => $this->segment->slug, // Adds to segments
                ],
            ],
            ['writeConcern' => new WriteConcern(1)]
        )->getModifiedCount();
    }

    /**
     * Returns an cursor of the customers that match the given segment
     *
     * @return array _ids of matched Customers.
     */
    protected function getCustomerIds(): array
    {
        // Set
        $parser = new ElasticsearchRulesetParser();
        $idOfHits = [];
        $remaining = 1;
        $esQueryClauses = $parser->parse($this->segment->ruleset());
        $esQueryClauses['_source'] = false;
        $esQueryClauses['size'] = 1000;
        $esQueryClauses['from'] = 0;

        // Loops in a way that it will make additional queries in order to
        // get all _ids of maching Customers.
        while ($remaining > 0) {
            $cursor = $this->customerEsQuery->get(
                $esQueryClauses,
                Customer::class
            );

            $idOfHits = array_merge($idOfHits, $cursor->getIdOfHits());
            $esQueryClauses['from'] += $esQueryClauses['size'];
            $remaining = $cursor->countPossible() - $esQueryClauses['from'];
        }

        return $idOfHits;
    }
}
