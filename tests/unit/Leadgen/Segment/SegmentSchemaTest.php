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
}
