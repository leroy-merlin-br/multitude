<?php

namespace Leadgen\Segment;

use SegmentSeeder;
use MongoDB\BSON\ObjectID;
use FunctionalTestCase;

class SegmentCrudTest extends FunctionalTestCase
{
    public function tearDown()
    {
        if ($segment = Segment::first('57aa7f2c037421193c333952')) {
            $segment->delete();
        }
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetTheListOfSegments()
    {
        // Given
        $this->haveSegmentsIntoDatabase();

        // When
        $this->get('/api/v1/segment');

        // Then
        $this->seeJson(['status' => 'success'])
            ->seeJsonStructure([
                'status',
                'content' => [
                    '*' => [
                        '_id',
                        'name',
                        'slug',
                        'rules',
                        'additionInterval',
                        'removalInterval'
                    ],
                ],
                'errors',
            ])
            ->seeStatusCode(200);
    }

    public function testShouldCreateANewSegment()
    {
        // Given
        $segmentData = [
            '_id'   => new ObjectID('57aa7f2c037421193c333952'),
            'name'  => 'Bathroom Project',
            'slug'  => 'bathroom-projetc',
            'rules' => [
                'find' => [
                    'interaction' => 'added-to-basket',
                    'category'    => ['Banheira', 'Vasos Sanitários'],
                ],
            ],
            'additionInterval' => '30 0 * * * *',
            'removalInterval'  => '0 0 * * * *',
        ];

        // When
        $this->post('/api/v1/segment', $segmentData);

        // Then
        $this->seeJson(['status' => 'created'])
            ->seeJsonStructure([
                'status',
                'content' => [
                    '_id',
                    'name',
                    'slug',
                    'rules',
                    'additionInterval',
                    'removalInterval'
                ],
                'errors',
            ])
            ->seeStatusCode(201);

        // When
        $this->get('/api/v1/segment/57aa7f2c037421193c333952');

        // Then
        $this->see('Bathroom Project')
            ->see('30 0 * * * *')
            ->seeJson(['status' => 'success'])
            ->seeJsonStructure([
                'status',
                'content' => [
                    '_id',
                    'name',
                    'slug',
                    'rules',
                    'additionInterval',
                    'removalInterval'
                ],
                'errors',
            ]);
    }

    public function testShouldNotCreateAnInvalidSegment()
    {
        // Given
        $segmentData = [
            '_id'   => new ObjectID('57aa7f2c037421193c333952'),
            'name'  => 'Bathroom Project',
            'slug'  => 'bathroom-projetc',
            'rules' => null, // Invalid
            'additionInterval' => '30 0 * * * *',
            'removalInterval'  => '0 0 * * * *',
        ];

        // When
        $this->post('/api/v1/segment', $segmentData);

        // Then
        $this->seeJson([
                'status' => 'bad request',
                'errors' => ['The rules field is required.'],
            ])
            ->seeStatusCode(400);
    }

    public function testShouldUpdateAnExistingSegment()
    {
        // Given
        $this->haveSegmentsIntoDatabase();
        $newData = [
            '_id'  => '57aa7f2c037421193c333952',
            'name' => 'Have garden',
            'slug' => 'have-garden',
        ];

        // When
        $this->put('/api/v1/segment/57aa7f2c037421193c333952', $newData);

        // Then
        $this->seeJson(['status' => 'success'])
            ->seeJsonStructure([
                'status',
                'content' => [
                    '_id',
                    'name',
                    'slug',
                    'rules',
                    'additionInterval',
                    'removalInterval'
                ],
                'errors',
            ])
            ->seeStatusCode(200);

        // When
        $this->get('/api/v1/segment/57aa7f2c037421193c333952');

        // Then
        $this->see('Have garden')
            ->see('have-garden')
            ->seeJson(['status' => 'success']);
    }

    protected function haveSegmentsIntoDatabase()
    {
        (new SegmentSeeder())->run();
    }
}
