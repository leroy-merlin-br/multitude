<?php

namespace Leadgen\Segment;

use Leadgen\Base\BaseEntity;

/**
 * Represents a customer segmentation.
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
     * Basic validation rules of a segment.
     *
     * @var array
     */
    public static $rules = [
        'name'  => 'required',
        'slug'  => 'required|alpha_dash',
        'rules' => 'required',
    ];

    /**
     * Embeds one Ruleset entity within the rules field
     *
     * @return Ruleset Embedded document
     */
    public function ruleset()
    {
        return $this->embedsOne(Ruleset::class, 'rules');
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
