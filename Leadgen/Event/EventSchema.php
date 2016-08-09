<?php
namespace Leadgen\Interaction;

use Mongolid\Schema;

/**
 * Describes the schema of an Interaction document
 */
class InteractionSchema extends Schema
{
    /**
     * Name of the collection where this kind of Entity is going to be saved or
     * retrieved from
     *
     * @var string
     */
    public $collection = 'interactions';

    /**
     * Name of the class that will be used to represent a document of this
     * Schema when retrieve from the database.
     *
     * @var string
     */
    public $entityClass = Interaction::class;

    /**
     * Tells how a document should look like.
     *
     * @var string[]
     */
    public $fields  = [
        '_id'         => 'objectId',
        'author'      => 'string',
        'authorId'    => 'string',
        'interaction' => 'objectId',
        'params'      => 'interactionFields',
        'created_at'  => 'createdAtTimestamp',
        'updated_at'  => 'updatedAtTimestamp'
    ];

    /**
     * Prepares the field to be the interaction fields.
     *
     * @param  mixed $value Value that will be evaluated.
     *
     * @return array
     */
    public function interactionFields($value = []): array
    {
        return $value;
    }
}
