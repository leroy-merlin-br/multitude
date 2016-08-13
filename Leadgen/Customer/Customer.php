<?php
namespace Leadgen\Customer;

use Leadgen\Base\BaseEntity;
use Leadgen\Interaction\Interaction;
use Mongolid\Cursor\CursorInterface;

class Customer extends BaseEntity
{
    /**
     * Describes the Schema fields of the model.
     *
     * @var  string
     */
    protected $fields = CustomerSchema::class;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'email' => 'email'
    ];

    /**
     * Customer embeds many Interaction
     *
     * @return CursorInterface
     */
    public function interactions(): CursorInterface
    {
        return $this->embedsMany(Interaction::class, 'interactions');
    }

    /**
     * Checks if the entity is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        foreach ($this->interactions() as $param) {
            if (! $param->isValid()) {
                $this->errors()->add('interactions', 'Invalid interaction object');
                $this->errors()->merge($param->errors());
                return false;
            }
        }

        return parent::isValid();
    }
}
