<?php
namespace Leadgen\InteractionType;

use Mongolid\Schema;

/**
 * The InteractionTypeSchema defines how a InteractionType document will look like
 */
class InteractionTypeSchema extends Schema
{
    /**
     * Name of the collection where this kind of Entity is going to be saved or
     * retrieved from
     *
     * @var string
     */
    public $collection = 'interactionTypes';

    /**
     * Name of the class that will be used to represent a document of this
     * Schema when retrieve from the database.
     *
     * @var string
     */
    public $entityClass = InteractionType::class;

    /**
     * Tells how a document should look like.
     *
     * @var string[]
     */
    public $fields  = [
        '_id' => 'objectId',
        'name' => 'string',
        'slug' => 'string',
        'params' => 'schema.'.ParamSchema::class,
        'created_at' => 'createdAtTimestamp',
        'updated_at' => 'updatedAtTimestamp'
    ];
}
