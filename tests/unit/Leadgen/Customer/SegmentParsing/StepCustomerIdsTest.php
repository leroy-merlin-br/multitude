<?php
namespace Leadgen\Customer\SegmentParsing;

use Infrastructure\Search\ElasticsearchCursor;
use Infrastructure\Search\ElasticsearchQuery;
use Leadgen\Customer\Customer;
use Mockery as m;
use PHPUnit_Framework_TestCase;

class StepCustomerIdsTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testShouldProcessCustomersIds()
    {
        // Arrange
        $esQuery = m::mock(ElasticsearchQuery::class);
        $dto = new Dto;
        $dto->esQueryClauses = [
            'foo' => 'bar',
            'from' => 0,
            'size' => 3
        ];
        $resultsCursor = m::mock(ElasticsearchCursor::class);

        $step = new StepCustomerIds($esQuery);

        // Act
        $esQuery->shouldReceive('get')
            ->times(3)
            ->with(m::AnyOf(
                ['foo' => 'bar', 'from' => 0, 'size' => 3],
                ['foo' => 'bar', 'from' => 3, 'size' => 3],
                ['foo' => 'bar', 'from' => 6, 'size' => 3]
            ), Customer::class)
            ->andReturn($resultsCursor);

        $resultsCursor->shouldReceive('getIdOfHits')
            ->times(3)
            ->andReturn([1,2,3], [4,5,6], [7,8,9]);

        $resultsCursor->shouldReceive('countPossible')
            ->andReturn(9);

        // Assert
        $this->assertEquals(
            [1, 2, 3, 4, 5, 6, 7, 8, 9],
            $step->parse($dto)->customerIds
        );
    }
}
