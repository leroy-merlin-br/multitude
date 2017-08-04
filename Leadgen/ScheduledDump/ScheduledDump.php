<?php

namespace Leadgen\ScheduledDump;

use Leadgen\Base\BaseEntity;
use MongoDB\BSON\ObjectID;

/**
 * Represents an scheduled dump to an external repository, which can be a ftp,
 * s3 bucket, sql database, etc. Scheduled dumps are entities that have a
 * periodicity in which the dumps will be made and the details of the external
 * place where the dump will be deposited.
 *
 * It basically exports the interactions in order to be used for BI, machine
 * learning and other big data tools.
 */
class ScheduledDump extends BaseEntity
{
    /**
     * Only be changed with 'fill' method.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'periodicity',
        'type',
        'settings',
    ];

    /**
     * Describes the Schema fields of the model.
     *
     * @var string
     */
    protected $fields = ScheduledDumpSchema::class;

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'name'        => 'string|required',
        'slug'        => 'required|alpha_dash',
        'description' => 'string',
        'periodicity' => 'string|required',
        'type'        => 'string',
        'settings'    => 'array',
    ];
}
