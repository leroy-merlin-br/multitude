<?php
namespace Leadgen\Segment;

use Leadgen\Base\SchemaFields\CronFieldTrait;
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

    public function testShouldContainCronFieldTrait()
    {
        $this->assertContains(
            CronFieldTrait::class,
            class_uses(SegmentSchema::class)
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
