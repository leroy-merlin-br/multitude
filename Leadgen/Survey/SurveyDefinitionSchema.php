<?php

namespace Leadgen\Survey;

use Mongolid\Schema;

/**
 * The SurveyDefinitionSchema defines how a SurveyDefinition document will look like.
 *
 * @SWG\Definition(
 *     type="object",
 *     definition="SurveyDefinition",
 *     required={"name", "slug", "params"}
 * )
 */
class SurveyDefinitionSchema extends Schema
{
    /**
     * Name of the collection where this kind of Entity is going to be saved or
     * retrieved from.
     *
     * @var string
     */
    public $collection = 'surveyDefinition';

    /**
     * Name of the class that will be used to represent a document of this
     * Schema when retrieve from the database.
     *
     * @var string
     */
    public $entityClass = SurveyDefinition::class;

    /**
     * Tells how a document should look like.
     *
     * @var string[]
     * @SWG\Property(
     *     property="_id",
     *     type="string",
     *     description="Unique identifier. (Generated automatically)"
     * ),
     * @SWG\Property(
     *     property="name",
     *     type="string",
     *     description="The name of the survey."
     * ),
     * @SWG\Property(
     *     property="slug",
     *     type="string",
     *     description="Slug that identifies the given survey. No space or special characters are allowed."
     * ),
     * @SWG\Property(
     *     property="params",
     *     type="array",
     *     description="Params of the given interaction",
     *     @SWG\Items(
     *         ref="#/definitions/Param",
     *     )
     * )
     */
    public $fields = [
        '_id'        => 'objectId',
        'name'       => 'string',
        'slug'       => 'string',
        'params'     => 'schema.'.ParamSchema::class,
        'created_at' => 'createdAtTimestamp',
        'updated_at' => 'updatedAtTimestamp',
    ];
}
