<?php

namespace Leadgen\InteractionType;

use Mongolid\Schema;

/**
 * Schema of an Param entity.
 *
 * @SWG\Definition(
 *     type="object",
 *     definition="Param",
 *     required={"name", "type", "required"}
 * )
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
     * @SWG\Property(
     *     property="_id",
     *     type="string",
     *     description="Unique identifier. (Generated automatically)"
     * ),
     * @SWG\Property(
     *     property="name",
     *     type="string",
     *     description="Name of parameter. No space or special characters are allowed.",
     * ),
     * @SWG\Property(
     *     property="type",
     *     type="string",
     *     description="Type of the parameter"
     * ),
     * @SWG\Property(
     *     property="required",
     *     type="boolean",
     *     description="Tells if the parameter is required."
     * )
     */
    public $fields = [
        '_id'      => 'objectId',
        'name'     => 'string',
        'type'     => 'string',
        'required' => 'boolean',
    ];
}
