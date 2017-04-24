<?php

use Illuminate\Database\Seeder;
use Leadgen\Authorization\Token;
use MongoDB\BSON\ObjectID;

class TokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->documentProvider() as $document) {
            if (Token::first($document['_id'])) {
                continue;
            }

            $token = new Token();
            $token->fill($document);

            if (!$token->save()) {
                $this->command->error($token->errors()->__toString());
            }
        }
    }

    protected function documentProvider()
    {
        return [
            // ----------------------
            'admin token' => [
                '_id'         => new ObjectID('58e62a2b03742163aa447b71'),
                'name'        => 'Super admin',
                'description' => 'Default initial token'
            ]
        ];
    }
}
