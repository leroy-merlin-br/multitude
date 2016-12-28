<?php
namespace Leadgen\Customer\SegmentParsing;

use Leadgen\Segment\ElasticsearchRulesetParser;
use Leadgen\Segment\Ruleset;
use Leadgen\Segment\Segment;
use Mockery as m;
use PHPUnit_Framework_TestCase;

class StepEsQueryTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testShouldProcessARuleset()
    {
        // Arrange
        $esRulesetParser = m::mock(ElasticsearchRulesetParser::class);
        $dto = new Dto;
        $dto->segment = m::mock(Segment::class);
        $ruleset = m::mock(Ruleset::class);

        $step = new StepEsQuery($esRulesetParser);

        // Act
        $dto->segment->shouldReceive('ruleset')
            ->once()
            ->andReturn($ruleset);

        $esRulesetParser->shouldReceive('parse')
            ->once()
            ->with($ruleset)
            ->andReturn(['query' => ['foo' => 'bar']]);

        // Assert
        $this->assertEquals(
            [
                'query' => ['foo' => 'bar'],
                '_source' => false,
                'size' => 1000,
                'from' => 0
            ],
            $step->parse($dto)->esQueryClauses
        );
    }
}
