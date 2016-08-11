<?php
namespace Leadgen\Interaction;

use Mongolid\Schema;

/**
 * Describes the schema of an Interaction document
 *
 * @SWG\Definition(
 *     type="object",
 *     definition="Interaction",
 * )
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
     * @SWG\Property(
     *     property="_id",
     *     type="string",
     *     description="Unique identifier of the interaction. (Generated automatically)"
     * ),
     * @SWG\Property(
     *     property="author",
     *     type="string",
     *     description="Email or docNumber of the customer that made the interaction."
     * ),
     * @SWG\Property(
     *     property="authorId",
     *     type="string",
     *     description="Unique identifier of the customer that made the interaction. (Generated based in the `author` property)"
     * ),
     * @SWG\Property(
     *     property="interaction",
     *     type="string",
     *     description="Unique identifier of the `InteractionType` that describes this interaction."
     * ),
     * @SWG\Property(
     *     property="params",
     *     type="object",
     *     description="The params of the interaction. A set of key-value properties that should follow the params described in the `InteractionType`."
     * )
     * @SWG\Property(
     *     property="acknowledged",
     *     type="boolean",
     *     default=false,
     *     description="Tells if the given `Interaction` have already been indexed in Elasticsearch."
     * ),
     */
    public $fields  = [
        '_id'          => 'objectId',
        'author'       => 'string',
        'authorId'     => 'string',
        'interaction'  => 'objectId',
        'params'       => 'interactionFields',
        'acknowledged' => 'bool',
        'created_at'   => 'createdAtTimestamp',
        'updated_at'   => 'updatedAtTimestamp'
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

    /**
     * Prepares a boolean field
     * @param  boolean $value Input
     * @return boolean
     */
    public function bool($value = false): bool
    {
        return (bool) $value;
    }
}
