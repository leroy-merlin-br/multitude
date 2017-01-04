<?php
namespace Leadgen\Customer;

use Leadgen\Customer\RulesetSchema;
use PHPUnit_Framework_TestCase;

class CustomerSchemaTest extends PHPUnit_Framework_TestCase
{
    public function testFieldShouldBeCorrect()
    {
        $this->assertAttributeEquals(
            [
                '_id'          => 'string',
                'docNumber'    => 'string',
                'email'        => 'string',
                'name'         => 'string',
                'interactions' => 'schema.'.InteractionSchema::class,
                'location'     => 'string',
                'segments'     => 'forceArray',
                'aggregated'   => 'forceArray',
                'created_at'   => 'createdAtTimestamp',
                'updated_at'   => 'updatedAtTimestamp',
            ],
            'fields',
            (new CustomerSchema)
        );
    }
}
