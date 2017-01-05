<?php
namespace Leadgen\Aggregation\InteractionParams;

use Leadgen\Customer\Customer;
use Leadgen\Interaction\Interaction;
use Mockery as m;
use Mongolid\Cursor\EmbeddedCursor;
use PHPUnit_Framework_TestCase;

class AggregatorTriggerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testShouldFireTrigger()
    {
        // Arrange
        $aggregator = m::mock(Aggregator::class);
        $trigger    = new AggregatorTrigger($aggregator);
        $settings   = ['fields' => ['foo' , 'bar']];
        $customers  = [
            m::mock(Customer::class.'[interactions,update]'),
            m::mock(Customer::class.'[interactions,update]')
        ];

        // Act
        foreach ($customers as $customer) {
            $customer->interactions = [m::mock(Interaction::class)];
            $customer->shouldReceive('interactions')
                ->once()
                ->andReturn(new EmbeddedCursor(Interaction::class, $customer->interactions));

            $aggregator->shouldReceive('aggregate')
                ->once()
                ->with(m::any(), ['foo', 'bar'])
                ->andReturnUsing(function ($interactions) use ($customer) {
                    if ($interactions->all() == $customer->interactions) {
                        return [1, 2, 3];
                    }
                });

            $customer->shouldReceive('update')
                ->once();
        }

        // Assert
        $this->assertTrue($trigger->fireTrigger($customers, $settings));
        foreach ($customers as $customer) {
            $this->assertEquals([1, 2, 3], $customer->aggregated);
        }
    }

    public function testShouldThrowExceptionIfCustomersIsNotIterable()
    {
        // Arrange
        $aggregator = m::mock(Aggregator::class);
        $trigger    = new AggregatorTrigger($aggregator);
        $settings   = ['fields' => ['foo' , 'bar']];
        $customers  = 2;

        // Act
        $this->setExpectedException(\InvalidArgumentException::class);

        // Assert
        $trigger->fireTrigger($customers, $settings);
    }

    public function testShouldReturnNullIfNoFieldsAreProvided()
    {
        // Arrange
        $aggregator = m::mock(Aggregator::class);
        $trigger    = new AggregatorTrigger($aggregator);
        $settings   = ['fields' => null];
        $customers  = [
            new Customer,
            new Customer,
        ];

        // Assert
        $this->assertNull(
            $trigger->fireTrigger($customers, $settings)
        );
    }
}
