<?php
namespace Leadgen\Segment;

use Leadgen\Ruleset\RulesetSchema;
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
                'rules'            => 'schema.'.RulesetSchema::class,
                'additionInterval' => 'cron',
                'removalInterval'  => 'cron',
                'created_at'       => 'createdAtTimestamp',
                'updated_at'       => 'updatedAtTimestamp',
            ],
            'fields',
            (new SegmentSchema)
        );
    }

    public function cronFieldDataProvider()
    {
        return [
            // $in, $out
            ['potato', null],
            [123456, null],
            ['* * *', null],
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
}
