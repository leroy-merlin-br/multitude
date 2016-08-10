<?php
namespace Leadgen\Customer;

use Leadgen\Base\BaseEntity;

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
}
