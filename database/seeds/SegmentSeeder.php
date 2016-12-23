<?php

use Illuminate\Database\Seeder;
use Leadgen\Segment\Segment;
use MongoDB\BSON\ObjectID;

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

            $segment = new Segment();
            $segment->fill($document);

            if (!$segment->save()) {
                $this->command->error($segment->errors()->__toString());
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
                'slug'  => 'bathroom-project',
                'ruleset' => [
                    '_id' => new ObjectID('58596c000374213cb9219751'),
                    'rules' => [
                        "condition" => "AND",
                        "rules" => [
                            [
                                "condition" => "AND",
                                "rules" => [
                                    [
                                        "id" => "interaction",
                                        "field" => "interaction",
                                        "type" => "string",
                                        "input" => "checkbox",
                                        "operator" => "in",
                                        "value" => [
                                            "visited-category"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'additionInterval' => '30 0 * * * *',
                'removalInterval'  => '0 0 * * * *',
            ],
        ];
    }
}
