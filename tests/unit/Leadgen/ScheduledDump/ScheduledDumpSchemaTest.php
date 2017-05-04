<?php

namespace Leadgen\ScheduledDump;

use Leadgen\Base\SchemaFields\CronFieldTrait;
use PHPUnit_Framework_TestCase;

class ScheduledDumpSchemaTest extends PHPUnit_Framework_TestCase
{
    public function testFieldShouldBeCorrect()
    {
        $this->assertAttributeEquals(
            [
                '_id'         => 'objectId',
                'name'        => 'string',
                'slug'        => 'string',
                'description' => 'string',
                'periodicity' => 'cron',
                'settings'    => 'array',
                'created_at'  => 'createdAtTimestamp',
                'updated_at'  => 'updatedAtTimestamp',
            ],
            'fields',
            (new ScheduledDumpSchema)
        );
    }

    public function testShouldContainCronFieldTrait()
    {
        $this->assertContains(
            CronFieldTrait::class,
            class_uses(ScheduledDumpSchema::class)
        );
    }
}
