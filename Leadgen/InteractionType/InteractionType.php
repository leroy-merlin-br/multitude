<?php
namespace Leadgen\InteractionType;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Leadgen\Interaction\Interaction;
use Leadgen\Base\BaseEntity;
use Mongolid\Cursor\CursorInterface;

/**
 * Entity that represents a schema of an Interaction.
 */
class InteractionType extends BaseEntity
{
    /**
     * Describes the Schema fields of the model.
     *
     * @var  string
     */
    protected $fields = InteractionTypeSchema::class;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'   => 'required',
        'slug'   => 'required|alpha_dash',
        'params' => 'required|array',
    ];

    /**
     * InteractionType embeds many Param objects
     *
     * @return CursorInterface
     */
    public function params(): CursorInterface
    {
        return $this->embedsMany(ParamSchema::class, 'params');
    }

    /**
     * Checks if the entity is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        foreach ($this->params() as $param) {
            if (! $param->isValid()) {
                $this->errors()->add('params', 'Invalid param object');
                $this->errors()->merge($param->errors());
                return false;
            }
        }

        return parent::isValid();
    }

    /**
     * Save the interaction type and updates it's mapping
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
     * Check if there is any error in the given Interaction based on the current
     * InteractionType
     *
     * @param  Interaction $interaction Interaction object being evaluated.
     *
     * @return array Errors
     */
    public function checkErrors(Interaction $interaction)
    {
        $rules = [];

        foreach ($this->params() as $param) {
            $rules[$param->name] = $param->type . ($param->required ? '|required' : '');
        }

        $validator = app(ValidationFactory::class)->make($interaction->params, $rules);
        return $validator->errors()->all();
    }

    /**
     * Prepare the Interaction mapping in Elasticsearch. This allow that new interactions
     * can be indexed with the params of the InteractionType.
     *
     * @return boolean
     */
    public function prepareMapping()
    {
        return app(ElasticsearchMapper::class)->map($this);
    }
}
