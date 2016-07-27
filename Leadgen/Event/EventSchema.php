<?php
namespace Leadgen\Event;

use Mongolid\Schema;

/**
 * Describes the schema of an Event document
 */
class EventSchema extends Schema
{
    /**
     * Name of the collection where this kind of Entity is going to be saved or
     * retrieved from
     *
     * @var string
     */
    public $collection = 'events';

    /**
     * Name of the class that will be used to represent a document of this
     * Schema when retrieve from the database.
     *
     * @var string
     */
    public $entityClass = Event::class;

    /**
     * Tells how a document should look like.
     *
     * @var string[]
     */
    public $fields  = [
        '_id'        => 'objectId',
        'author'     => 'string',
        'authorId'   => 'string',
        'event'      => 'objectId',
        'params'     => 'eventFields',
        'created_at' => 'createdAtTimestamp',
        'updated_at' => 'updatedAtTimestamp'
    ];

    /**
     * Prepares the field to be the event fields.
     *
     * @param  mixed $value Value that will be evaluated.
     *
     * @return array
     */
    public function eventFields($value = []): array
    {
        return $value;
    }
}
