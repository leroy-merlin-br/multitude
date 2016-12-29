<?php
namespace Leadgen\Customer\SegmentParsing;

use Leadgen\Segment\Segment;
use Mockery as m;
use PHPUnit_Framework_TestCase;

class SegmentParserTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testShouldParseTheWholeChainOfSteps()
    {
        // Arrange
        $dependencies = [
            'stepEsQuery' => m::mock(StepEsQuery::class),
            'stepCustomerIds' => m::mock(StepCustomerIds::class),
            'stepFireTriggers' => m::mock(StepFireTriggers::class),
            'stepUpdateCustomers' => m::mock(StepUpdateCustomers::class)
        ];
        $segment = m::mock(Segment::class);

        // Act
        foreach ($dependencies as $dep) {
            $dep->shouldAllowMockingProtectedMethods()
                ->shouldReceive('process')
                ->once()
                ->andReturnUsing(function ($dto) use ($segment) {
                    $this->assertEquals($segment, $dto->segment);
                    return $dto;
                });
        }

        // Assert
        $segmentParser = new SegmentParser(...array_values($dependencies));
        $segmentParser->parse($segment);
    }
}
