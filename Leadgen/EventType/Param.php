<?php
namespace Leadgen\EventType;

use Leadgen\Base\BaseEntity;

/**
 * Entity that represents a EventType Param.
 */
class Param extends BaseEntity
{
    /**
     * Describes the Schema fields of the model.
     *
     * @var  string
     */
    protected $fields = ParamSchema::class;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'     => 'required|alpha',
        'type'     => 'required|in:integer,numeric,string',
        'required' => 'required|boolean',
    ];
}
