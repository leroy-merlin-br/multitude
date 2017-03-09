<?php

namespace Leadgen\FunctionalTest\Feature;

use FunctionalTestCase;
use Leadgen\Customer\Customer;
use Leadgen\Interaction\Interaction;
use MongoDB\BSON\ObjectID;

/**
 * @feature I, responsible for segmentation,
 *          wish to create a segmentation of Customers based in a list of
 *          behaviors that they performed, in order to be able to work with
 *          'clusters' of customers.
 */
class SegmentParsingTest extends FunctionalTestCase
{
    public function testShouldParseSegmentsAndMarkCustomersThatArePartOfThen()
    {
        // Given
        $this->haveIntoDatabase('InteractionType');
        $this->haveIntoDatabase('Segment');

        $this->haveCustomer('johndoe@test.com');
        $this->interactionHappened([
            '_id'         => new ObjectID('57ac88a20374215a026fd783'),
            'author'      => 'johndoe@test.com',
            'interaction' => 'visited-category',
            'channel'     => 'web',
            'location'    => 'sao_paulo',
            'params'      => [
                'category'  => 'Roots and Vegetables',
            ],
        ]);

        $this->haveCustomer('murphy@test.com');
        $this->interactionHappened([
            '_id'         => new ObjectID('57ac88a20374215a026fd784'),
            'author'      => 'murphy@test.com',
            'interaction' => 'visited-product',
            'channel'     => 'web',
            'location'    => 'sao_paulo',
            'params'      => [
                'productId' => 7,
                'category'  => 'Potato',
            ],
        ]);

        // When
        $this->runCommand('leadgen:segment-update', ['segmentslug' => 'bathroom-project']);

        // Then
        $this->customerShouldBeInSegment('johndoe@test.com', 'bathroom-project');
        $this->customerShouldNotBeInSegment('murphy@test.com', 'bathroom-project');
    }

    public function tearDown()
    {
        $this->cleanCollection('interactionTypes');
        $this->cleanCollection('interactions');
        $this->cleanCollection('segments');
        $this->cleanCollection('customers');
    }

    protected function haveIntoDatabase(string $entityName)
    {
        $className = "{$entityName}Seeder";
        (new $className())->run();
    }

    protected function haveCustomer($email)
    {
        $customer = new Customer;
        $customer->email = $email;
        $customer->save();
    }

    protected function interactionHappened($interactionData)
    {
        $interaction = new Interaction;
        $interaction->fill($interactionData);
        $this->setProtected($interaction, 'writeConcern', 1);
        $interaction->save();
        $this->runCommand('leadgen:proc-interaction');
        $this->waitElasticsearchOperations();
    }

    protected function customerShouldBeInSegment($email, $segment)
    {
        $customer = Customer::first(['email' => $email]);

        $this->assertContains($segment, $customer->segments);
    }

    protected function customerShouldNotBeInSegment($email, $segment)
    {
        $customer = Customer::first(['email' => $email]);

        $this->assertNotContains($segment, $customer->segments);
    }
}
