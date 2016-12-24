<?php

namespace Leadgen\Segment;

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
     */
    public $fields = [
        '_id'              => 'objectId',
        'name'             => 'string',
        'slug'             => 'string',
        'ruleset'          => 'schema.'.RulesetSchema::class,
        'additionInterval' => 'cron',
        'removalInterval'  => 'cron',
        'created_at'       => 'createdAtTimestamp',
        'updated_at'       => 'updatedAtTimestamp',
    ];

    /**
     * Prepares a field to be a cron string or null.
     *
     * @param mixed $value Value that will be evaluated.
     *
     * @return string
     */
    public function cron($value = '0 0 * * * *')
    {
        $value = trim($value);
        $cronPattern = '/^(\S+) (\S+) (\S+) (\S+) (\S+) (\S+)$/';

        if (preg_match($cronPattern, $value)) {
            return $value;
        };

        return '0 0 * * * *';
    }
}
