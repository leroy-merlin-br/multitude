<?php
namespace Leadgen\Customer;

use Leadgen\Interaction\InteractionSchema;
use Mongolid\Schema;

/**
 * The CustomerSchema defines how a Customer document will look like
 *
 * @SWG\Definition(
 *     type="object",
 *     definition="Customer",
 * )
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
     * @SWG\Property(
     *     property="_id",
     *     type="string",
     *     description="Unique identifier of the customer. (Generated automatically)"
     * ),
     * @SWG\Property(
     *     property="docNumber",
     *     type="string",
     *     description="An document number that identify this customer. May be an CRM number for example."
     * ),
     * @SWG\Property(
     *     property="email",
     *     type="string",
     *     description="Email of the customer."
     * ),
     * @SWG\Property(
     *     property="name",
     *     type="string",
     *     description="Name of the customer."
     * ),
     * @SWG\Property(
     *     property="interactions",
     *     type="array",
     *     description="Interactions that this customer has made.",
     *     @SWG\Items(
     *         ref="#/definitions/Interaction",
     *     )
     * )
     */
    public $fields  = [
        '_id' => 'string',
        'docNumber' => 'string',
        'email' => 'string',
        'name' => 'string',
        'interactions' => 'schema.'.InteractionSchema::class,
        'created_at' => 'createdAtTimestamp',
        'updated_at' => 'updatedAtTimestamp'
    ];
}
