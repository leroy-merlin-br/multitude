<?php

namespace Leadgen\Segment;

use Mongolid\Schema;

/**
 * The RulesetSchema defines how a Ruleset document will look like.
 *
 * @SWG\Definition(
 *     type="object",
 *     definition="Ruleset",
 *     required={"rules"}
 * )
 */
class RulesetSchema extends Schema
{
    /**
     * Name of the collection where this kind of Entity is going to be saved or
     * retrieved from.
     *
     * @var string
     */
    public $collection = null;

    /**
     * Name of the class that will be used to represent a document of this
     * Schema when retrieve from the database.
     *
     * @var string
     */
    public $entityClass = Ruleset::class;

    /**
     * Tells how a document should look like.
     *
     * @var string[]
     * @SWG\Property(
     *     property="_id",
     *     type="string",
     *     description="Unique identifier of the rule set. (Generated automatically)"
     * ),
     * @SWG\Property(
     *     property="rules",
     *     type="array",
     *     description="Rules that determines if a customers is part of the segment.",
     *     @SWG\Items(
     *         type="object"
     *     )
     * )
     */
    public $fields = [
        '_id'   => 'objectId',
        'rules' => 'array',
    ];
}
