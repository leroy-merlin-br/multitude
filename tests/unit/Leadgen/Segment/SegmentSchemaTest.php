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
            // $in, $out
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
    public function testShouldParseCronField($in, $out)
    {
        $this->assertEquals(
            $out,
            (new SegmentSchema)->cron($in)
        );
    }

    public function influenceFieldDataProvider()
    {
        $default = [];

        return [
            // $in, $out
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
    public function testShouldParseInfluenceField($in, $out)
    {
        $this->assertEquals(
            $out,
            (new SegmentSchema)->influence($in)
        );
    }
}
