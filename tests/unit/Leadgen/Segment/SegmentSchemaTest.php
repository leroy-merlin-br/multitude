<?php
namespace Leadgen\Segment;

use Leadgen\Segment\RulesetSchema;
use PHPUnit_Framework_TestCase;

class SegmentSchemaTest extends PHPUnit_Framework_TestCase
{
    public function testFieldShouldBeCorrect()
    {
        $this->assertAttributeEquals(
            [
                '_id'              => 'objectId',
                'name'             => 'string',
                'slug'             => 'string',
                'ruleset'          => 'schema.'.RulesetSchema::class,
                'additionInterval' => 'cron',
                'removalInterval'  => 'cron',
                'triggers'         => 'schema.'.TriggerSchema::class,
                'influence'        => 'influence',
                'created_at'       => 'createdAtTimestamp',
                'updated_at'       => 'updatedAtTimestamp',
            ],
            'fields',
            (new SegmentSchema)
        );
    }

    public function cronFieldDataProvider()
    {
        $default = '0 0 * * * *';

        return [
            // $input, $output
            ['potato', $default],
            [123456, $default],
            ['* * *', $default],
            ['* * * * * *', '* * * * * *'],
            [' * * * * * *   ', '* * * * * *'],
            ['   * * 1 * 2 *', '* * 1 * 2 *'],
        ];
    }

    /**
     * @dataProvider cronFieldDataProvider
     */
    public function testShouldParseCronField($input, $output)
    {
        $this->assertEquals(
            $output,
            (new SegmentSchema)->cron($input)
        );
    }

    public function influenceFieldDataProvider()
    {
        $default = [];

        return [
            // $input, $output
            [null, $default],
            [123456, $default],
            ['something', $default],
            [(new \stdClass), $default],
            [['foo' => 25], ['foo' => 25]],
            [['foo' => 25, 'bar' => 'faz'], ['foo' => 25]],
            [['foo' => 25, 'bar' => 10], ['foo' => 25, 'bar' => 10]],
        ];
    }

    /**
     * @dataProvider influenceFieldDataProvider
     */
    public function testShouldParseInfluenceField($input, $output)
    {
        $this->assertEquals(
            $output,
            (new SegmentSchema)->influence($input)
        );
    }
}
