<?php

namespace Leadgen\InteractionType;

use InteractionTypeSeeder;
use MongoDB\BSON\ObjectID;
use FunctionalTestCase;

class InteractionTypeCrudTest extends FunctionalTestCase
{
    public function tearDown()
    {
        if ($interactionType = InteractionType::first('57aa81fb0374aa1940330001')) {
            $interactionType->delete();
        }
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetTheListOfInteractionTypes()
    {
        // Given
        $this->haveInteractionsIntoDatabase();

        // When
        $this->get('/api/v1/interactionType');

        // Then
        $this->seeJson(['status' => 'success'])
            ->seeJsonStructure([
                'status',
                'content' => [
                    '*' => [
                        '_id',
                        'name',
                        'slug',
                        'params' => [
                            '*' => [
                                '_id',
                                'name',
                                'type',
                            ],
                        ],
                    ],
                ],
                'errors',
            ])
            ->seeStatusCode(200);
    }

    public function testShouldCreateANewInteractionType()
    {
        // Given
        $interactionTypeData = [
            '_id'    => new ObjectID('57aa81fb0374aa1940330001'),
            'name'   => 'Posted a review',
            'slug'   => 'posted-review',
            'params' => [
                [
                    'name'     => 'product-id',
                    'type'     => 'numeric',
                    'required' => true,
                ],
                [
                    'name'     => 'rating',
                    'type'     => 'numeric',
                    'required' => false,
                ],
            ],
        ];

        // When
        $this->post('/api/v1/interactionType', $interactionTypeData);

        // Then
        $this->seeJson(['status' => 'created'])
            ->seeJsonStructure([
                'status',
                'content' => [
                    '_id',
                    'name',
                    'slug',
                    'params' => [
                        '*' => [
                            '_id',
                            'name',
                            'type',
                        ],
                    ],
                ],
                'errors',
            ])
            ->seeStatusCode(201);

        // When
        $this->get('/api/v1/interactionType/57aa81fb0374aa1940330001');

        // Then
        $this->see('Posted a review')
            ->seeJson(['status' => 'success'])
            ->seeJsonStructure([
                'status',
                'content' => [
                    '_id',
                    'name',
                    'slug',
                    'params' => [
                        '*' => [
                            '_id',
                            'name',
                            'type',
                        ],
                    ],
                ],
                'errors',
            ]);
    }

    public function testShouldNotCreateAnInvalidInteractionType()
    {
        // Given
        $interactionTypeData = [
            '_id'    => new ObjectID('57aa81fb0374aa1940330001'),
            'name'   => 'Posted a review',
            'slug'   => null, // Invalid
            'params' => [
                [
                    'name'     => 'product-id',
                    'type'     => 'numeric',
                    'required' => true,
                ],
            ],
        ];

        // When
        $this->post('/api/v1/interactionType', $interactionTypeData);

        // Then
        $this->seeJson([
                'status' => 'bad request',
                'errors' => ['The slug field is required.'],
            ])
            ->seeStatusCode(400);
    }

    public function testShouldUpdateAnExistingInteractionType()
    {
        // Given
        $this->haveInteractionsIntoDatabase();
        $newData = [
            '_id'  => '57aa7f2c037421193c333952',
            'name' => 'Looked for',
            'slug' => 'looked-for',
        ];

        // When
        $this->put('/api/v1/interactionType/57aa7f2c037421193c333952', $newData);

        // Then
        $this->seeJson(['status' => 'success'])
            ->seeJsonStructure([
                'status',
                'content' => [
                    '_id',
                    'name',
                    'slug',
                    'params' => [
                        '*' => [
                            '_id',
                            'name',
                            'type',
                        ],
                    ],
                ],
                'errors',
            ])
            ->seeStatusCode(200);

        // When
        $this->get('/api/v1/interactionType/57aa7f2c037421193c333952');

        // Then
        $this->see('Looked for')
            ->seeJson(['status' => 'success']);
    }

    protected function haveInteractionsIntoDatabase()
    {
        (new InteractionTypeSeeder())->run();
    }

    protected function see($content)
    {
        $this->assertContains(
            $content,
            $this->response->getContent(),
            "Couldn't find $content in response content."
        );

        return $this;
    }
}
