<?php

namespace Leadgen\Interaction;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\MessageBag;
use Leadgen\InteractionType\InteractionType;
use Leadgen\InteractionType\Repository as InteractionTypeRepo;
use Mockery as m;
use Mongolid\Exception\ModelNotFoundException;
use PHPUnit_Framework_TestCase;

/**
 * @covers Leadgen\Interaction\Interaction
 */
class InteractionTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
        app()->forgetInstance(InteractionTypeRepo::class);
        app()->forgetInstance(ValidationFactory::class);
    }

    public function testShouldHaveCorrectDatabaseSchema()
    {
        // Assert
        $this->assertAttributeEquals(
            InteractionSchema::class,
            'fields',
            (new Interaction())
        );
    }

    public function testShouldReferenceAnInteractionType()
    {
        // Arrange
        $interaction = m::mock(Interaction::class.'[referencesOne]');
        $interactionType = m::mock(InteractionType::class);

        // Act
        $interaction->shouldAllowMockingProtectedMethods();

        $interaction->shouldReceive('referencesOne')
            ->once()
            ->with(InteractionType::class, 'interactionId')
            ->andReturn($interactionType);

        // Assert
        $this->assertSame($interactionType, $interaction->interactionType());
    }

    public function testShouldSanitizeItsFields()
    {
        // Arrange
        $interaction = new Interaction();
        $interactionTypeRepo = m::mock();
        $interactionType = (object) ['_id' => 123];

        $interaction->fill([
            'author'      => 'foo%40bar.com',
            'interaction' => 'done-something',
        ], true);

        // Act
        app()->instance(InteractionTypeRepo::class, $interactionTypeRepo);

        $interactionTypeRepo->shouldReceive('findExisting')
            ->with(['slug' => 'done-something'])
            ->andReturn($interactionType);

        // Assert
        $interaction->sanitize();

        $this->assertEquals(
            [
                'author'        => 'foo@bar.com',
                'authorId'      => md5('foo@bar.com'),
                'interaction'   => 'done-something',
                'interactionId' => 123,
            ],
            $interaction->attributes
        );
    }

    public function validationDataProvider()
    {
        return [
            // ----------------------
            'all valid' => [
                '$areAttributesValid'             => true,
                '$interactionTypeAttributeErrors' => [],
                '$expected'                       => true,
            ],

            // // ----------------------
            'base validation failed' => [
                '$areAttributesValid'             => false,
                '$interactionTypeAttributeErrors' => [],
                '$expected'                       => false,
            ],

            // ----------------------
            'interaction type validation fails' => [
                '$areAttributesValid'             => false,
                '$interactionTypeAttributeErrors' => ['There is a problem with the interaction paramas'],
                '$expected'                       => false,
            ],

            // ----------------------
            'with model not found exception' => [
                '$areAttributesValid'             => true,
                '$interactionTypeAttributeErrors' => [],
                '$expected'                       => false,
                '$exception'                      => true,
            ],
        ];
    }

    /**
     * @dataProvider validationDataProvider
     */
    public function testShouldValidateAttributes($areAttributesValid, $interactionTypeAttributeErrors, $expected, $exception = false)
    {
        // Arrange
        $interaction = m::mock(Interaction::class.'[interactionType,sanitize]');
        $interactionType = m::mock();
        $lumenValidation = m::mock();

        // Act
        app()->instance(ValidationFactory::class, $lumenValidation);

        $interaction->shouldReceive('sanitize')
            ->andReturn(true);

        $lumenValidation->shouldReceive('make')
            ->andReturn($lumenValidation);

        $lumenValidation->shouldReceive('fails')
            ->andReturn(!$areAttributesValid);

        $lumenValidation->shouldReceive('errors')
            ->andReturn(new MessageBag(['damn']));

        if ($exception) {
            $interaction->shouldReceive('interactionType')
                ->andThrow(ModelNotFoundException::class);
        } else {
            $interaction->shouldReceive('interactionType')
                ->andReturn($interactionType);
        }

        $interactionType->shouldReceive('checkErrors')
            ->with($interaction)
            ->andReturn($interactionTypeAttributeErrors);

        // Assert
        $this->assertEquals($expected, $interaction->isValid());
    }

    public function saveDataProvider()
    {
        return [
            // ----------------------
            'no errors' => [
                '$errors'   => [],
                '$expected' => true,
            ],

            // ----------------------
            'with errors' => [
                '$errors'   => ['foo', 'bar'],
                '$expected' => false,
            ],
        ];
    }

    /**
     * @dataProvider saveDataProvider
     */
    public function testSaveShouldReturnTrueIfModelIsValid($errors, $expected)
    {
        // Arrange
        $errorsBag = new MessageBag($errors);
        $interaction = m::mock(Interaction::class.'[isValid,errors,execute]');

        // Act
        $interaction->shouldAllowMockingProtectedMethods();

        $interaction->shouldReceive('execute')
            ->once()
            ->with('save')
            ->andReturn(false);

        $interaction->shouldReceive('isValid')
            ->once()
            ->andReturn(true);

        $interaction->shouldReceive('errors')
            ->once()
            ->andReturn($errorsBag);

        // Assert
        $this->assertEquals($expected, $interaction->save());
    }
}
