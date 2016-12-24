<?php

namespace Leadgen\Customer;

use Leadgen\Interaction\Interaction;
use Leadgen\Segment\Segment;
use Leadgen\Segment\Repository as SegmentRepository;
use Mockery as m;
use Mongolid\Cursor\Cursor;
use Mongolid\Cursor\EmbeddedCursor;
use PHPUnit_Framework_TestCase;

/**
 * @covers Leadgen\Customer\Customer
 */
class CustomerTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
        app()->forgetInstance(SegmentRepository::class);
    }

    public function testShouldHaveCorrectDatabaseSchema()
    {
        // Assert
        $this->assertAttributeEquals(
            CustomerSchema::class,
            'fields',
            (new Customer())
        );
    }

    public function testShouldHaveCorrectFieldValidationRules()
    {
        // Arrange
        $expected = [
            'email'   => 'email',
        ];

        // Assert
        $this->assertEquals($expected, Customer::$rules);
    }

    public function testShouldEmbedsManyInteractions()
    {
        // Arrange
        $customer = m::mock(Customer::class.'[embedsMany]');
        $interactionCursor = m::mock(EmbeddedCursor::class);

        // Act
        $customer->shouldAllowMockingProtectedMethods();

        $customer->shouldReceive('embedsMany')
            ->once()
            ->with(Interaction::class, 'interactions')
            ->andReturn($interactionCursor);

        // Assert
        $this->assertSame($interactionCursor, $customer->interactions());
    }

    public function testShouldReferenceManySegmentsBySlug()
    {
        // Arrange
        $customer = new Customer;
        $segmentRepo = m::mock(SegmentRepository::class);
        $segmentCursor = m::mock(Cursor::class);

        $customer->segments = ['foo', 'bar'];

        // Act
        app()->instance(SegmentRepository::class, $segmentRepo);

        $segmentRepo->shouldReceive('where')
            ->once()
            ->with(['slug' => ['$in' => $customer->segments]])
            ->andReturn($segmentCursor);

        // Assert
        $this->assertSame($segmentCursor, $customer->segments());
    }
}
