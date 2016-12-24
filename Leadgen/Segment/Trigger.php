<?php
namespace Leadgen\Segment;

use Leadgen\Base\BaseEntity;

/**
 * The Trigger entity represents a kind of a "method" that will be executed
 * whenever a Customer joins or leaves a segment.
 */
class Trigger extends BaseEntity
{
    /**
     * Describes the schema of the entity.
     *
     * @var string
     */
    protected $fields = TriggerSchema::class;

    /**
     * Basic validation rules of a ruleset.
     *
     * @var array
     */
    public static $rules = [
        'type' => 'required|string',
        'settings' => 'string',
    ];
}
