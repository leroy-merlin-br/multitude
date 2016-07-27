<?php
namespace Leadgen\EventType;

use Elasticsearch\Client;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Leadgen\Event\Event;
use Leadgen\Base\BaseEntity;
use Mongolid\Cursor\CursorInterface;

/**
 * Entity that represents a schema of an Event.
 */
class EventType extends BaseEntity
{
    /**
     * Describes the Schema fields of the model.
     *
     * @var  string
     */
    protected $fields = EventTypeSchema::class;

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
     * EventType embeds many Param objects
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
     * Check if there is any error in the given Event based on the current
     * EventType
     *
     * @param  Event $event Event object being evaluated.
     *
     * @return array Errors
     */
    public function checkErrors(Event $event)
    {
        $rules = [];

        foreach ($this->params() as $param) {
            $rules[$param->name] = $param->type . ($param->required ? '|required' : '');
        }

        $validator = app(ValidationFactory::class)->make($event->params, $rules);
        return $validator->errors()->all();
    }

    /**
     * Prepare the Event mapping in Elasticsearch. This allow that new events
     * can be indexed with the params of the EventType.
     *
     * @return boolean
     */
    public function prepareMapping()
    {
        return app(ElasticsearchMapper::class)->map($this);
    }
}
