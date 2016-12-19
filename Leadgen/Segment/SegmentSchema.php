<?php

namespace Leadgen\Segment;

use Mongolid\Schema;

/**
 * The SegmentSchema defines how a Segment document will look like.
 *
 * @SWG\Definition(
 *     type="object",
 *     definition="Segment",
 *     required={"name", "slug", "rules"}
 * )
 */
class SegmentSchema extends Schema
{
    /**
     * Name of the collection where this kind of Entity is going to be saved or
     * retrieved from.
     *
     * @var string
     */
    public $collection = 'segments';

    /**
     * Name of the class that will be used to represent a document of this
     * Schema when retrieve from the database.
     *
     * @var string
     */
    public $entityClass = Segment::class;

    /**
     * Tells how a document should look like.
     *
     * @var string[]
     * @SWG\Property(
     *     property="_id",
     *     type="string",
     *     description="Unique identifier of the segment. (Generated automatically)"
     * ),
     * @SWG\Property(
     *     property="name",
     *     type="string",
     *     description="Name to identify the segment."
     * ),
     * @SWG\Property(
     *     property="slug",
     *     type="string",
     *     description="A clean string to identify the segment."
     * ),
     * @SWG\Property(
     *     property="rules",
     *     type="object",
     *     description="The ruleset that determines if a customers is part of the segment.",
     *     ref="#/definitions/Ruleset"
     * )
     */
    public $fields = [
        '_id'        => 'objectId',
        'name'       => 'string',
        'slug'       => 'string',
        'rules'      => 'schema.'.RulesetSchema::class,
        'created_at' => 'createdAtTimestamp',
        'updated_at' => 'updatedAtTimestamp',
    ];
}
