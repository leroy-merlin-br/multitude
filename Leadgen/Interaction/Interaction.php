<?php
namespace Leadgen\Interaction;

use Leadgen\Base\BaseEntity;
use Leadgen\InteractionType\InteractionType;
use Leadgen\InteractionType\Repository as InteractionTypeRepo;
use Mongolid\Exception\ModelNotFoundException;

/**
 * Represents an single interaction by an individual
 */
class Interaction extends BaseEntity
{
    /**
     * Describes the Schema fields of the model.
     *
     * @var  string
     */
    protected $fields = InteractionSchema::class;

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
        'interaction'    => 'required',
        'interactionId'  => 'required',
        'params'   => 'required|array'
    ];

    /**
     * References one interactionType
     *
     * @return InteractionType
     */
    public function interactionType()
    {
        return $this->referencesOne(InteractionType::class, 'interactionId');
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

        if (empty($this->interactionId)) {
            $interactionType = app()->make(InteractionTypeRepo::class)
                ->findExisting(['slug' => $this->interaction]);
            $this->interactionId = $interactionType->_id;
        }
    }

    /**
     * Checks if the entity is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        try {
            $this->sanitize();

            if (! $result = parent::isValid()) {
                return $result;
            }

            $errors = $this->interactionType()->checkErrors($this);
        } catch (ModelNotFoundException $e) {
            $errors = ["interactionId doesn't corresponds to an existing InteractionType"];
        }

        if (! empty($errors)) {
            $this->errors()->merge($errors);
            return false;
        }

        return true;
    }

    /**
     * Overwrites save method in order to return true if the entity was valid.
     * Since Interactions have write concern as zero this is needed in order to know
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
