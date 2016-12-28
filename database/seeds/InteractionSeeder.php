<?php

use Illuminate\Database\Seeder;
use Leadgen\Interaction\Interaction;
use MongoDB\BSON\ObjectID;

class InteractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->documentProvider() as $document) {
            if (Interaction::first($document['_id'])) {
                continue;
            }

            $interactionType = new Interaction();
            $interactionType->fill($document);

            if (!$interactionType->save()) {
                $this->command->error($interactionType->errors()->__toString());
            }
        }
    }

    protected function documentProvider()
    {
        return [
            // ----------------------
            'searched' => [
                '_id'         => new ObjectID('57ac87f00374215a016fd782'),
                'author'      => 'zizaco@gmail.com',
                'interaction' => 'searched',
                'channel'     => 'web',
                'location'    => 'sao_paulo',
                'params'      => [
                    'term' => 'Quality potatoes',
                ],
            ],

            // ----------------------
            'visited-product' => [
                '_id'         => new ObjectID('57ac88a20374215a026fd783'),
                'author'      => 'zizaco@gmail.com',
                'interaction' => 'visited-product',
                'channel'     => 'web',
                'location'    => 'sao_paulo',
                'params'      => [
                    'productId' => 1,
                    'category'  => 'Roots and Vegetables',
                ],
            ],

            // ----------------------
            'visited-product' => [
                '_id'         => new ObjectID('57ac88a20374215a026fd783'),
                'author'      => 'zizaco@gmail.com',
                'interaction' => 'visited-category',
                'channel'     => 'web',
                'location'    => 'sao_paulo',
                'params'      => [
                    'category'  => 'Roots and Vegetables',
                ],
            ],

            // ----------------------
            'added-to-basket' => [
                '_id'         => new ObjectID('57ac896d0374215b566fd784'),
                'author'      => 'zizaco@gmail.com',
                'interaction' => 'added-to-basket',
                'channel'     => 'web',
                'location'    => 'sao_paulo',
                'params'      => [
                    'productId' => 1,
                    'price'     => 1.90,
                    'category'  => 'Roots and Vegetables',
                ],
            ],

            // ----------------------
            'purchased-products' => [
                '_id'         => new ObjectID('57ac89b80374215cc56fd786'),
                'author'      => 'zizaco@gmail.com',
                'interaction' => 'purchased-products',
                'channel'     => 'web',
                'location'    => 'sao_paulo',
                'params'      => [
                    'productId' => 1,
                    'total'     => 1.90,
                    'category'  => 'Roots and Vegetables',
                ],
            ],
        ];
    }
}
