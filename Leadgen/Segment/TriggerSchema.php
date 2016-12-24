<?php

namespace Leadgen\Segment;

use Mongolid\Schema;

/**
 * The TriggerSchema defines how a Trigger document will look like.
 *
 * @SWG\Definition(
 *     type="object",
 *     definition="Trigger",
 *     required={"rules"}
 * )
 */
class TriggerSchema extends Schema
{
    /**
     * Name of the collection where this kind of Entity is going to be saved or
     * retrieved from.
     *
     * @var string
     */
    public $collection = null;

    /**
     * Name of the class that will be used to represent a document of this
     * Schema when retrieve from the database.
     *
     * @var string
     */
    public $entityClass = Trigger::class;

    /**
     * Tells how a document should look like.
     *
     * @var string[]
     * @SWG\Property(
     *     property="_id",
     *     type="string",
     *     description="Unique identifier of the rule set. (Generated automatically)"
     * ),
     * @SWG\Property(
     *     property="type",
     *     type="string",
     *     description="Identifies which trigger/logic will be executed."
     * ),
     * @SWG\Property(
     *     property="settings",
     *     type="object",
     *     description="A settings object that will be read in trigger excecution.",
     * )
     */
    public $fields = [
        '_id'   => 'objectId',
        'type' => 'string',
        'settings' => 'array'
    ];
}
