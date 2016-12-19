<?php

namespace Leadgen\InteractionType;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\MessageBag;
use Mockery as m;
use Mongolid\Cursor\CursorInterface;
use PHPUnit_Framework_TestCase;

/**
 * @covers Leadgen\InteractionType\InteractionType
 */
class InteractionTypeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
        app()->forgetInstance(ValidationFactory::class);
        app()->forgetInstance(ElasticsearchMapper::class);
    }

    public function testShouldHaveCorrectDatabaseSchema()
    {
        // Assert
        $this->assertAttributeEquals(
            InteractionTypeSchema::class,
            'fields',
            (new InteractionType())
        );
    }

    public function testShouldHaveCorrectFieldValidationRules()
    {
        // Arrange
        $expected = [
            'name'   => 'required',
            'slug'   => 'required|alpha_dash',
            'params' => 'required|array',
        ];

        // Assert
        $this->assertEquals($expected, InteractionType::$rules);
    }

    public function testShouldEmbedParams()
    {
        // Arrange
        $interactionType = m::mock(InteractionType::class.'[embedsMany]');
        $interactionType->shouldAllowMockingProtectedMethods();
        $params = m::mock(CursorInterface::class);

        // Act
        $interactionType->shouldReceive('embedsMany')
            ->once()
            ->with(ParamSchema::class, 'params')
            ->andReturn($params);

        // Assert
        $this->assertSame($params, $interactionType->params());
    }

    public function testShouldValidateAttributes()
    {
        // Arrange
        $lumenValidator = m::mock(ValidationFactory::class);
        $interactionType = new InteractionType();
        $params = [
            m::mock(Param::class),
            m::mock(Param::class),
        ];
        $interactionType->params = $params;

        // Act
        app()->instance(ValidationFactory::class, $lumenValidator);

        $lumenValidator->shouldReceive('make')
            ->andReturn($lumenValidator)
            ->getMock()
            ->shouldReceive('fails')
            ->andReturn(false);

        $params[0]->shouldReceive('isValid')
            ->andReturn(true);

        $params[1]->shouldReceive('isValid')
            ->andReturn(false)
            ->getMock()
            ->shouldReceive('errors')
            ->andReturn(new MessageBag(['potato']));

        // Assert
        $this->assertFalse($interactionType->isValid());

        $this->assertEquals(
            ['Invalid param object', 'potato'],
            $interactionType->errors()->all()
        );
    }

    public function testShouldPrepareMappingAfterSuccessfullSave()
    {
        // Arrange
        $interactionType = m::mock(InteractionType::class.'[execute,isValid]');
        $interactionType->shouldAllowMockingProtectedMethods();

        // Act
        $this->shouldPrepareMappingOf($interactionType);

        $interactionType->shouldReceive('isValid')
            ->andReturn(true);

        $interactionType->shouldReceive('execute')
            ->once()
            ->with('save')
            ->andReturn(true);

        // Assert
        $interactionType->save();
    }

    public function testShouldNotPrepareMappingAfterAFailedSave()
    {
        // Arrange
        $interactionType = m::mock(InteractionType::class.'[execute,isValid]');
        $interactionType->shouldAllowMockingProtectedMethods();

        // Act
        $this->shouldNotPrepareMappingOf($interactionType);

        $interactionType->shouldReceive('isValid')
            ->andReturn(true);

        $interactionType->shouldReceive('execute')
            ->once()
            ->with('save')
            ->andReturn(false);

        // Assert
        $interactionType->save();
    }

    protected function shouldPrepareMappingOf($entity)
    {
        $elasticsearchMapper = m::mock(ElasticsearchMapper::class);

        $elasticsearchMapper->shouldReceive('map')
            ->once()
            ->with($entity);

        app()->instance(ElasticsearchMapper::class, $elasticsearchMapper);
    }

    protected function shouldNotPrepareMappingOf($entity)
    {
        $elasticsearchMapper = m::mock(ElasticsearchMapper::class);

        $elasticsearchMapper->shouldReceive('map')
            ->never()
            ->with($entity);

        app()->instance(ElasticsearchMapper::class, $elasticsearchMapper);
    }
}
