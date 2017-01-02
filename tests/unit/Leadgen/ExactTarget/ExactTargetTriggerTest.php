<?php
namespace Leadgen\ExactTarget;

use Mockery as m;
use Mongolid\Cursor\Cursor;
use PHPUnit_Framework_TestCase;

class ExactTargetTriggerTest extends PHPUnit_Framework_TestCase
{
    public function testShouldFireTrigger()
    {
        // Arrange
        $customerUpdater = m::mock(CustomerUpdater::class);
        $trigger = new ExactTargetTrigger($customerUpdater);
        $customers = m::mock(Cursor::class);
        $settings = [
            'dataExtension' => 'fooBar'
        ];
        $results = m::mock();

        // Act
        $customerUpdater->shouldReceive('send')
            ->once()
            ->with($customers, 'fooBar')
            ->andReturn($results);

        // Assert
        $this->assertEquals(
            $results,
            $trigger->fireTrigger($customers, $settings)
        );
    }
}
