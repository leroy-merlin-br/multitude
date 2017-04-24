<?php

namespace Leadgen\ScheduledDump;

use Leadgen\Base\SchemaFields\CronFieldTrait;
use Mongolid\Schema;

/**
 * The ScheduledDumpSchema defines how a ScheduledDump document will look like.
 *
 * @SWG\Definition(
 *     type="object",
 *     definition="ScheduledDump",
 *     required={"name", "periodicity", "type", "settings"}
 * )
 */
class ScheduledDumpSchema extends Schema
{
    use CronFieldTrait;

    /**
     * Name of the collection where this kind of Entity is going to be saved or
     * retrieved from.
     *
     * @var string
     */
    public $collection = 'scheduled_dumps';

    /**
     * Name of the class that will be used to represent a document of this
     * Schema when retrieve from the database.
     *
     * @var string
     */
    public $entityClass = ScheduledDump::class;

    /**
     * Tells how a document should look like.
     *
     * @var string[]
     * @SWG\Property(
     *     property="_id",
     *     type="string",
     *     description="Unique identifier of the scheduled dump. (Generated automatically)"
     * ),
     * @SWG\Property(
     *     property="name",
     *     type="string",
     *     description="Name to identify the scheduled dump."
     * ),
     * @SWG\Property(
     *     property="description",
     *     type="string",
     *     description="A description of the scheduled dump. Can be used to identify it later."
     * ),
     * @SWG\Property(
     *     property="periodicity",
     *     type="string",
     *     description="Periodicity (in cron-job format) to perform the dump.",
     *     default="30 0 * * * *"
     * ),
     * @SWG\Property(
     *     property="type",
     *     type="string",
     *     description="Identifies which dump logic will be executed."
     * ),
     * @SWG\Property(
     *     property="settings",
     *     type="object",
     *     description="A settings object that will be read when executing the dump.",
     * )
     */
    public $fields = [
        '_id'         => 'objectId',
        'name'        => 'string',
        'description' => 'string',
        'periodicity' => 'cron',
        'settings'    => 'array',
        'created_at'  => 'createdAtTimestamp',
        'updated_at'  => 'updatedAtTimestamp',
    ];
}
