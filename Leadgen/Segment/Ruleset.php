<?php

namespace Leadgen\Segment;

use Leadgen\Base\BaseEntity;

/**
 * Ruleset represents an set of rules that matches some specific customers.
 * Roughly, it is a query that is going to be performed in Elasticsearch in
 * order to actually bring the Customers from the database.
 */
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
