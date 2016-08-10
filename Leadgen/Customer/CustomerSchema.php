<?php
namespace Leadgen\Customer;

use Leadgen\Interaction\InteractionSchema;
use Mongolid\Schema;

/**
 * The CustomerSchema defines how a Customer document will look like
 */
class CustomerSchema extends Schema
{
    /**
     * Name of the collection where this kind of Entity is going to be saved or
     * retrieved from
     *
     * @var string
     */
    public $collection = 'customers';

    /**
     * Name of the class that will be used to represent a document of this
     * Schema when retrieve from the database.
     *
     * @var string
     */
    public $entityClass = Customer::class;

    /**
     * Tells how a document should look like.
     *
     * @var string[]
     */
    public $fields  = [
        '_id' => 'objectId',
        'identifier' => 'string',
        'email' => 'string',
        'name' => 'string',
        'interactions' => 'schema.'.InteractionSchema::class,
        'created_at' => 'createdAtTimestamp',
        'updated_at' => 'updatedAtTimestamp'
    ];
}
