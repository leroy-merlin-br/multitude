<?php

use Illuminate\Database\Seeder;
use Leadgen\InteractionType\InteractionType;
use MongoDB\BSON\ObjectID;

class InteractionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->documentProvider() as $document) {
            if (InteractionType::first($document['_id'])) {
                continue;
            }

            $interactionType = new InteractionType;
            $interactionType->fill($document);

            if (! $interactionType->save()) {
                $this->command->error($interactionType->errors()->__toString());
            }
        }
    }

    protected function documentProvider()
    {
        return [
            // ----------------------
            'searched' => [
                '_id' => new ObjectID('57aa7f2c037421193c333952'),
                'name' => "Searched",
                'slug' => 'searched',
                'params' => [
                    [
                        'name' => 'term',
                        'type' => 'string',
                        'required' => false,
                    ],
                ]
            ],

            // ----------------------
            'oppened-email' => [
                '_id' => new ObjectID('57aa81fb0374211940333953'),
                'name' => "Openned email",
                'slug' => 'openned-email',
                'params' => [
                    [
                        'name' => 'subject',
                        'type' => 'string',
                        'required' => false,
                    ],
                ]
            ],

            // ----------------------
            'visited-category' => [
                '_id' => new ObjectID('57aa82030374211d28333954'),
                'name' => "Visited category",
                'slug' => 'visited-category',
                'params' => [
                    [
                        'name' => 'category',
                        'type' => 'string',
                        'required' => true,
                    ],
                ]
            ],

            // ----------------------
            'visited-content' => [
                '_id' => new ObjectID('57aa820d0374211d2f333955'),
                'name' => "Visited content",
                'slug' => 'visited-content',
                'params' => [
                    [
                        'name' => 'contentId',
                        'type' => 'numeric',
                        'required' => true,
                    ],
                    [
                        'name' => 'contentName',
                        'type' => 'string',
                        'required' => true,
                    ],
                    [
                        'name' => 'category',
                        'type' => 'string',
                        'required' => false,
                    ],
                ]
            ],

            // ----------------------
            'visited-product' => [
                '_id' => new ObjectID('57aa821e0374211d48333956'),
                'name' => "Visited product",
                'slug' => 'visited-product',
                'params' => [
                    [
                        'name' => 'productId',
                        'type' => 'numeric',
                        'required' => true,
                    ],
                    [
                        'name' => 'category',
                        'type' => 'string',
                        'required' => false,
                    ],
                ]
            ],

            // ----------------------
            'added-to-basket' => [
                '_id' => new ObjectID('57aa82270374211d5c333957'),
                'name' => "See product",
                'slug' => 'added-to-basket',
                'params' => [
                    [
                        'name' => 'productId',
                        'type' => 'numeric',
                        'required' => true,
                    ],
                    [
                        'name' => 'price',
                        'type' => 'numeric',
                        'required' => true,
                    ],
                    [
                        'name' => 'category',
                        'type' => 'string',
                        'required' => false,
                    ],
                ]
            ],

            // ----------------------
            'purchased' => [
                '_id' => new ObjectID('57aa822f0374211d65333958'),
                'name' => "Purchased products",
                'slug' => 'purchased-products',
                'params' => [
                    [
                        'name' => 'productId',
                        'type' => 'numeric',
                        'required' => true,
                    ],
                    [
                        'name' => 'total',
                        'type' => 'numeric',
                        'required' => true,
                    ],
                    [
                        'name' => 'details',
                        'type' => 'string',
                        'required' => false,
                    ],
                ]
            ],
        ];
    }
}

