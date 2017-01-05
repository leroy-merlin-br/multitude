<?php
namespace Leadgen\Customer\SegmentParsing;

use Leadgen\Customer\Customer;
use MongoDB\Driver\WriteConcern;

/**
 * Call updateMany in mongodb in order to add the segment to the Customer
 * documents
 *
 * @see SegmentParser
 */
class StepUpdateCustomers extends StepBase
{
    /**
     * Customer instance used to call a database query
     * @var Customer
     */
    protected $customer;

    /**
     * Injects the dependencies.
     *
     * @param Customer $customer An empty Customer instance (used to call a database update).
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Process this step of the chain.
     *
     * @param  Dto $dto The input data.
     * @return Dto $dto after parsing.
     */
    protected function process(Dto $dto): Dto
    {
        if (! isset($dto->customerIds) || ! isset($dto->segment)) {
            return $dto;
        }

        $dto->affectedCount = $this->callUpdate($dto);

        return $dto;
    }

    /**
     * Call updateMany in mongodb in order to add the segment to the Customer
     * documents
     *
     * @param  Dto $dto Dto containing the _ids of the customers.
     *
     * @return int Number of affected documents.
     */
    protected function callUpdate(Dto $dto): int
    {
        $result = $this->customer->collection()->updateMany(
            [
                '_id' => [
                    '$in' => $dto->customerIds,
                ],
                'segments' => [
                    '$exists' => true,
                    '$gte' => [], // Checks if type is array
                    '$ne' => $dto->segment->slug // To avoid redundancy
                ]
            ],
            [
                '$addToSet' => [
                    'segments' => $dto->segment->slug, // Adds to segments
                ],
            ],
            ['writeConcern' => new WriteConcern(1)]
        )->getModifiedCount();

        return $result ?: 0;
    }
}
