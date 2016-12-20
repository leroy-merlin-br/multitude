<?php

namespace Leadgen\Segment;

use Leadgen\Base\BaseEntity;

class Ruleset extends BaseEntity
{
    /**
     * Describes the schema of the entity.
     *
     * @var string
     */
    protected $fields = RulesetSchema::class;

    /**
     * Basic validation rules of a ruleset.
     *
     * @var array
     */
    public static $rules = [
        'rules' => 'required|array',
    ];
}
