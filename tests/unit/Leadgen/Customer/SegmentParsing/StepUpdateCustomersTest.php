<?php
namespace Leadgen\Customer\SegmentParsing;

use Leadgen\Customer\Customer;
use Mockery as m;
use MongoDB\Collection;
use MongoDB\Driver\WriteConcern;
use PHPUnit_Framework_TestCase;

class StepUpdateCustomersTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testShouldUpdateCustomers()
    {
        // Arrange
        $customer = m::mock(Customer::class);
        $collection = m::mock(Collection::class);
        $result = m::mock();

        $dto = new Dto;
        $dto->customerIds = [1,2,3];
        $dto->segment = (object)['slug' => 'tha-segment'];

        $step = new StepUpdateCustomers($customer);

        // Act
        $customer->shouldReceive('collection')
            ->once()
            ->andReturn($collection);

        $collection->shouldReceive('updateMany')
            ->once()
            ->with(
                [
                    '_id' => [
                        '$in' => $dto->customerIds,
                    ],
                    'segments' => [
                        '$exists' => true,
                        '$gte' => [],
                        '$ne' => 'tha-segment'
                    ]
                ],
                [
                    '$addToSet' => [
                        'segments' => 'tha-segment',
                    ],
                ],
                ['writeConcern' => new WriteConcern(1)]
            )->andReturn($result);

        $result->shouldReceive('getModifiedCount')
            ->andReturn(3);

        // Assert
        $this->assertEquals(
            3,
            $step->parse($dto)->affectedCount
        );
    }
}
