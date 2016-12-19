<?php

namespace Leadgen\Interaction;

use PHPUnit_Framework_TestCase;

class InteractionSchemaTest extends PHPUnit_Framework_TestCase
{
    public function testShouldPrepareInteractionFields()
    {
        // Assert
        $this->assertEquals(
            [],
            (new InteractionSchema())->interactionFields()
        );
    }

    public function testShouldParseBoolFields()
    {
        // Assert
        $this->assertSame(
            false,
            (new InteractionSchema())->bool(null)
        );
    }
}
