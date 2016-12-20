<?php

namespace Leadgen\Segment;

use Leadgen\Base\BaseEntity;

/**
 * Represents a customer segmentation. An segmentation have an identification (
 * name, _id, and slug), but also an Ruleset that matches some customers.
 *
 * The 'additionInterval' and 'removalInterval' are the frequency in which the
 * segment will test look for new customers that are part of it, and to remove
 * customers that are no longer matches by the Ruleset (respectivelly).
 */
class Segment extends BaseEntity
{
    /**
     * Describes the schema of the entity.
     *
     * @var string
     */
    protected $fields = SegmentSchema::class;

    /**
     * Basic validation of a segment attributes
     *
     * @var array
     */
    public static $rules = [
        'name'  => 'required',
        'slug'  => 'required|alpha_dash',
        'ruleset' => 'required',
    ];

    /**
     * Embeds one Ruleset entity within the ruleset field
     *
     * @return Ruleset Embedded document
     */
    public function ruleset()
    {
        return $this->embedsOne(Ruleset::class, 'ruleset');
    }

    /**
     * Validates embedded ruleset and merges the results
     *
     * @return boolean Valid
     */
    public function isValid()
    {
        if (!$result = parent::isValid()) {
            return $result;
        }

        if ($this->ruleset()->isValid()) {
            return true;
        }

        $this->errors()->merge($this->ruleset()->errors());

        return false;
    }
}
