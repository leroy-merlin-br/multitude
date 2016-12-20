<?php
namespace Leadgen\Segment;

use Illuminate\Contracts\Validation\Factory as ValidationFactoryContract;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Factory as ValidationFactory;
use Mockery as m;
use PHPUnit_Framework_TestCase;

class SegmentTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testShouldHaveTheCorrectSchema()
    {
        // Assert
        $this->assertAttributeEquals(
            SegmentSchema::class,
            'fields',
            (new Segment)
        );
    }

    public function testShouldEmbedsOneRuleset()
    {
        // Arrange
        $segment = m::mock(Segment::class.'[embedsOne]');
        $ruleset = new Ruleset;

        // Act
        $segment->shouldAllowMockingProtectedMethods();

        $segment->shouldReceive('embedsOne')
            ->once()
            ->with(Ruleset::class, 'ruleset')
            ->andReturn($ruleset);

        // Assert
        $this->assertSame(
            $ruleset,
            $segment->ruleset()
        );
    }

    public function validationDataProvider()
    {
        return [
            // $areAttributesValid, $isRulesetValid, $rulesetErrors, $expectedOutput
            [true, true, [], true],
            [false, true, [], false],
            [true, false, ['foo'], false],
            [true, false, ['foo', 'bar'], false],
        ];
    }

    /**
     * @dataProvider validationDataProvider
     */
    public function testShouldValidateItsEmbeddedDocument(
        $areAttributesValid,
        $isRulesetValid,
        $rulesetErrors,
        $expectedOutput
    ) {
        // Arrange
        $lumenValidator = m::mock();
        $segment        = new Segment;
        $ruleset        = m::mock(Ruleset::class);

        $segment->fill([
            'name'  => 'Bathroom Project',
            'slug'  => 'bathroom-projetc',
            'ruleset' => [$ruleset],
            'additionInterval' => '30 0 * * * *',
            'removalInterval'  => '0 0 * * * *',
        ]);


        // Act
        app()->instance(ValidationFactoryContract::class, $lumenValidator);

        $lumenValidator->shouldReceive('make')
            ->andReturn($lumenValidator);

        $lumenValidator->shouldReceive('fails')
            ->andReturn(! $areAttributesValid);

        $lumenValidator->shouldReceive('errors')
            ->andReturn(new MessageBag());

        $ruleset->shouldReceive('isValid')
            ->andReturn($isRulesetValid);

        $ruleset->shouldReceive('errors')
            ->andReturn(new MessageBag($rulesetErrors));

        // Assert
        $this->assertEquals($expectedOutput, $segment->isValid());
        $this->assertEquals(count($rulesetErrors), $segment->errors()->count());
    }
}
