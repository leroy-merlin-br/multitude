<?php

namespace Leadgen\Authorization;

use Mongolid\Schema;

/**
 * Describes the schema of an Token document.
 *
 * @SWG\Definition(
 *     type="object",
 *     definition="AuthToken",
 *     required={"name"}
 * )
 */
class TokenSchema extends Schema
{
    /**
     * Name of the collection where this kind of Entity is going to be saved or
     * retrieved from.
     *
     * @var string
     */
    public $collection = 'auth_tokens';

    /**
     * Name of the class that will be used to represent a document of this
     * Schema when retrieve from the database.
     *
     * @var string
     */
    public $entityClass = Token::class;

    /**
     * Tells how a document should look like.
     *
     * @var string[]
     * @SWG\Property(
     *     property="_id",
     *     type="string",
     *     description="Unique identifier of the token. (Generated automatically)"
     * ),
     * @SWG\Property(
     *     property="name",
     *     type="string",
     *     description="Token name in order to be identified later."
     * ),
     * @SWG\Property(
     *     property="secret",
     *     type="string",
     *     description="Token secret that will be used to contextualize an request with this token. (Generated automatically)"
     * ),
     * @SWG\Property(
     *     property="description",
     *     type="string",
     *     description="A description of the token. Can be used to identify the token later."
     * ),
     */
    public $fields = [
        '_id'         => 'objectId',
        'name'        => 'string',
        'secret'      => 'string',
        'description' => 'string',
        'created_at'  => 'createdAtTimestamp',
        'updated_at'  => 'updatedAtTimestamp',
    ];
}
