<?php
namespace Leadgen\InteractionType;

use Mongolid\Schema;

/**
 * Schema of an Param entity
 */
class ParamSchema extends Schema
{
    /**
     * Name of the class that will be used to represent a document of this
     * Schema when retrieve from the database.
     *
     * @var string
     */
    public $entityClass = Param::class;

    /**
     * Tells how a document should look like.
     *
     * @var string[]
     */
    public $fields  = [
        '_id'      => 'objectId',
        'name'     => 'string',
        'type'     => 'string',
        'required' => 'boolean',
    ];
}
