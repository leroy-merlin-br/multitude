<?php

use Illuminate\Database\Seeder;
use Leadgen\Segment\Segment;
use Mongolid\Serializer\Type\ObjectID;

class SegmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->documentProvider() as $document) {
            if (Segment::first($document['_id'])) {
                continue;
            }

            $interactionType = new Segment();
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
            'bathroomProjetc' => [
                '_id'   => new ObjectID('57aa7f2c037421193c333952'),
                'name'  => 'Bathroom Project',
                'slug'  => 'bathroom-projetc',
                'rules' => [
                    'find' => [
                        'interaction' => 'added-to-basket',
                        'category'    => ['Banheira', 'Vasos Sanitários'],
                    ],
                ],
            ],
        ];
    }
}
