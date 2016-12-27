<?php
namespace Leadgen\Customer\SegmentParsing;

/**
 * Call updateMany in mongodb in order to add the segment to the Customer
 * documents
 *
 * @see SegmentParser
 */
class UpdateCustomers extends StepBase
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
        if (! isset($dto->customerIds)) {
            return $dto;
        }

        $dto->affectedCount = $this->callUpdate($dto->customerIds);

        return $dto;
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
        return $this->customer->collection()->updateMany(
            [
                '_id' => [
                    '$in' => $customerIds,
                ],
                'segments' => [
                    '$exists' => true,
                    '$gte' => [], // Checks if type is array
                    '$ne' => $this->segment->slug // To avoid redundancy
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
}
