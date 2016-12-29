<?php
namespace Leadgen\Customer\SegmentParsing;

use Leadgen\Customer\Repository as CustomerRepository;
use Leadgen\Segment\Segment;
use Leadgen\Segment\Trigger;
use Mockery as m;
use Mongolid\Cursor\Cursor;
use PHPUnit_Framework_TestCase;
use PHProutine\Channel;
use PHProutine\Runner;

class StepFireTriggersTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testShouldFireTriggersOfTheSegment()
    {
        // Arrange
        $phproutine     = m::mock(Runner::class);
        $customerRepo   = m::mock(CustomerRepository::class);
        $triggerFire    = m::mock();
        $customerCursor = m::mock(Cursor::class);
        $dto            = new Dto;
        $trigger        = new Trigger;
        $step           = new StepFireTriggers($phproutine, $customerRepo);

        $dto->segment = m::mock(Segment::class);
        $dto->customerIds = [1,2,3];

        $trigger->type = 'TriggerFire';
        $trigger->settings = ['foo' => 'bar'];

        // Act
        app()->instance('TriggerFire', $triggerFire);

        $dto->segment->shouldReceive('triggers')
            ->once()
            ->andReturn([$trigger]);

        $phproutine->shouldReceive('go')
            ->andReturnUsing(function($closure, ...$params) {
                $closure(...$params);
            });

        $triggerFire->shouldReceive('fireTrigger')
            ->once()->with($customerCursor, $trigger->settings)
            ->andReturn(true);

        $customerRepo->shouldReceive('where')
            ->with(['_id' => ['$in' => $dto->customerIds]])
            ->andReturn($customerCursor);

        // Assert
        $this->assertEquals(
            true,
            unserialize($step->parse($dto)->triggerResult->read())
        );

    }
}
