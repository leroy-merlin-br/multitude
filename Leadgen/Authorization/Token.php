<?php

namespace Leadgen\Authorization;

use Leadgen\Base\BaseEntity;
use MongoDB\BSON\ObjectID;

/**
 * Represents an individual access token. Tokens are used to indentify different
 * sources of information or third-parties that interacts with the API.
 */
class Token extends BaseEntity
{
    /**
     * Only be changed with 'fill' method.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Generates random token and secret.
     */
    public function __construct()
    {
        $this->_id = new ObjectID;
        $this->secret = uniqid(bin2hex(random_bytes(2)), true);
    }

    /**
     * Describes the Schema fields of the model.
     *
     * @var string
     */
    protected $fields = TokenSchema::class;

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'name'        => 'string|required',
        'secret'      => 'string|required',
        'description' => 'string',
    ];
}
