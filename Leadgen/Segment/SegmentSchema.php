<?php

namespace Leadgen\Segment;

use Leadgen\Base\SchemaFields\CronFieldTrait;
use Mongolid\Schema;

/**
 * The SegmentSchema defines how a Segment document will look like.
 *
 * @SWG\Definition(
 *     type="object",
 *     definition="Segment",
 *     required={"name", "slug", "ruleset"}
 * )
 */
class SegmentSchema extends Schema
{
    use CronFieldTrait;

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
     *     property="ruleset",
     *     type="object",
     *     description="The ruleset that determines if a customers is part of the segment.",
     *     ref="#/definitions/Ruleset"
     * ),
     * @SWG\Property(
     *     property="additionInterval",
     *     type="string",
     *     description="Interval (in cron-job format) to test and add new customers are part of the given segment.",
     *     default="30 0 * * * *"
     * ),
     * @SWG\Property(
     *     property="removalInterval",
     *     type="string",
     *     description="Interval (in cron-job format) to re-test and remove users from the segment.",
     *     default="0 0 * * * *"
     * ),
     * @SWG\Property(
     *     property="triggers",
     *     type="array",
     *     description="An array of triggers entities that will be executed whenever a Customer joins or leaves a segment.",
     *     @SWG\Items(
     *         ref="#/definitions/Trigger",
     *     )
     * ),
     * @SWG\Property(
     *     property="influence",
     *     type="object",
     *     description="An influence object. An influence object is a series of 'key, pair' where the keys are strings and the values are exclusivelly integers. They are later used to know if an user matches a new segment or if he have a Project."
     * ),
     */
    public $fields = [
        '_id'              => 'objectId',
        'name'             => 'string',
        'slug'             => 'string',
        'ruleset'          => 'schema.'.RulesetSchema::class,
        'additionInterval' => 'cron',
        'removalInterval'  => 'cron',
        'triggers'         => 'schema.'.TriggerSchema::class,
        'influence'        => 'influence',
        'created_at'       => 'createdAtTimestamp',
        'updated_at'       => 'updatedAtTimestamp',
    ];

    /**
     * Prepares a field the be an 'influence' associative array.
     *
     * An influence associative array is a series of 'key, pair' where the
     * keys are strings and the values are exclusivelly integers. They are
     * later used to know if an user matches a new segment or if he have a
     * Project.
     *
     * @param  mixed $value Value that will be parsed.
     * @return array
     */
    public function influence($value = [])
    {
        if (!is_array($value)) {
            return [];
        }

        foreach ($value as $key => $amount) {
            if (!is_numeric($amount)) {
                unset($value[$key]);
            }
        }

        return $value;
    }
}
