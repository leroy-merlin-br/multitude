<?php
namespace Leadgen\Segment;

use Leadgen\Base\BaseEntity;

/**
 * Represents a customer segmentation
 */
class Segment extends BaseEntity
{
    /**
     * Describes the schema of the entity
     *
     * @var string
     */
    protected $fields = SegmentSchema::class;

    /**
     * Basic validation rules of a segment
     *
     * @var array
     */
    public static $rules = [
        'name'  => 'required',
        'slug'  => 'required|alpha_dash',
        'rules' => 'required'
    ];
}
