<?php
namespace Leadgen\Base\SchemaFields;

use PHPUnit_Framework_TestCase;

class CronFieldTraitTest extends PHPUnit_Framework_TestCase
{
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
        $class = new class {
            use CronFieldTrait;
        };

        $this->assertEquals(
            $output,
            (new $class)->cron($input)
        );
    }
}
