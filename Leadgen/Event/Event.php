<?php
namespace Leadgen\Event;

use Leadgen\EventType\EventType;
use Leadgen\EventType\Repository as EventTypeRepo;
use Leadgen\Base\BaseEntity;

/**
 * Represents an single event by an individual
 */
class Event extends BaseEntity
{
    /**
     * Describes the Schema fields of the model.
     *
     * @var  string
     */
    protected $fields = EventSchema::class;

    /**
     * Disables write concern to optimze write performance.
     *
     * @see https://docs.mongodb.com/manual/reference/write-concern/
     * @var integer
     */
    protected $writeConcern = 0;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'author'   => 'required',
        'authorId' => 'required',
        'event'    => 'required',
        'eventId'  => 'required',
        'params'   => 'required|array'
    ];

    /**
     * References one eventType
     *
     * @return EventType
     */
    public function eventType()
    {
        return $this->referencesOne(EventType::class, 'eventId');
    }

    /**
     * Sanitizes entity attributes
     *
     * @return void
     */
    public function sanitize()
    {
        if (empty($this->authorId)) {
            $this->authorId = md5($this->author);
        }

        if (empty($this->eventId)) {
            $eventType = app()->make(EventTypeRepo::class)
                ->findExisting(['slug' => $this->event]);
            $this->eventId = $eventType->_id;
        }
    }

    /**
     * Checks if the entity is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        $this->sanitize();
        $errors = $this->eventType()->checkErrors($this);

        if (! empty($errors)) {
            $this->errors()->merge($errors);
            return false;
        }

        return parent::isValid();
    }

    /**
     * Overwrites save method in order to return true if the entity was valid.
     * Since Events have write concern as zero this is needed in order to know
     * if the entity was good enought to be sent to the database and not the
     * real database save return.
     *
     * @param boolean $force Force save even if the object is invalid.
     *
     * @return boolean Success
     */
    public function save(bool $force = false)
    {
        parent::save($force);

        if ($this->errors()->count()) {
            return false;
        }

        return true;
    }
}
