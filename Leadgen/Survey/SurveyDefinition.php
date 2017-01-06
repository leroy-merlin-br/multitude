<?php

namespace Leadgen\Survey;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Leadgen\Base\BaseEntity;
use Leadgen\Survey\Survey;
use Mongolid\Cursor\CursorInterface;

/**
 * Entity that represents a schema and definition of a Survey. The information
 * and the questionsthat will be displayed to the Customer.
 */
class SurveyDefinition extends BaseEntity
{
    /**
     * Describes the Schema fields of the model.
     *
     * @var string
     */
    protected $fields = SurveyDefinitionSchema::class;

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'name'      => 'required',
        'slug'      => 'required|alpha_dash',
        'questions' => 'required|array',
    ];

    /**
     * SurveyDefinition embeds many Param objects.
     *
     * @return CursorInterface
     */
    public function questions(): CursorInterface
    {
        return $this->embedsMany(ParamSchema::class, 'questions');
    }

    /**
     * Checks if the entity is valid.
     *
     * @return boolean
     */
    public function isValid()
    {
        foreach ($this->questions() as $param) {
            if (!$param->isValid()) {
                $this->errors()->add('questions', 'Invalid param object');
                $this->errors()->merge($param->errors());

                return false;
            }
        }

        return parent::isValid();
    }

    /**
     * Save the interaction type and updates it's mapping.
     *
     * @param boolean $force Force save even if the object is invalid.
     *
     * @return boolean
     */
    public function save(bool $force = false)
    {
        if ($result = parent::save($force)) {
            $this->prepareMapping();
        }

        return $result;
    }

    /**
     * Check if there is any error in the given Survey based on the current
     * SurveyDefinition.
     *
     * @param Survey $interaction Survey object being evaluated.
     *
     * @return array Errors
     */
    public function checkErrors(Survey $interaction)
    {
        $rules = [];

        foreach ($this->questions() as $param) {
            $paramName = $param->name;
            if (is_array(array_get($interaction->questions, $param->name))) {
                $paramName .= '.*';
            }

            $rules[$paramName] = $param->type.($param->required ? '|required' : '');
        }

        $validator = app(ValidationFactory::class)->make($interaction->questions, $rules);

        return $validator->errors()->all();
    }

    /**
     * Prepare the Survey mapping in Elasticsearch. This allow that new
     * answers can be indexed with the questions of the SurveyDefinition.
     *
     * @return boolean
     */
    public function prepareMapping()
    {
        return app(ElasticsearchMapper::class)->map($this);
    }
}
