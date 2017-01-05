<?php
namespace Leadgen\Customer;

use Leadgen\Interaction\InteractionSchema;
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

    public function forceArrayDataProvider()
    {
        $default = [];

        return [
            // $in, $out
            [null, $default],
            [123456, $default],
            ['foo', ['foo']],
            [[1, 2, 3], [1, 2, 3]],
            [['foo', 'bar', 'fuz'], ['foo', 'bar', 'fuz']]
        ];
    }

    /**
     * @dataProvider forceArrayDataProvider
     */
    public function testShouldParseForceArray($in, $out)
    {
        $this->assertEquals(
            $out,
            (new CustomerSchema)->forceArray($in)
        );
    }
}
