<?php

namespace Leadgen\FunctionalTest\Feature;

use FunctionalTestCase;
use Leadgen\Customer\Customer;
use Leadgen\Interaction\Interaction;
use MongoDB\BSON\ObjectID;

/**
 * @feature I, developer,
 *          wish that multitude parses the new interactions that happened
 *          and assign then to new and existing customers based on the email
 *          of the interactions. In order to be able quickly retrieve
 *          interactions that 'belongs to' a customer.
 */
class InteractionProcessingTest extends FunctionalTestCase
{
    public function testShouldProcessInteractionsAndAssignThenNewOrExistingCustomers()
    {
        // Given
        $this->haveIntoDatabase('InteractionType');

        $this->interactionReceived([
            '_id'         => new ObjectID('56bd88a20374215a026fd786'),
            'author'      => 'steve_bowl@test.com',
            'interaction' => 'visited-category',
            'channel'     => 'web',
            'location'    => 'rio_de_janeiro',
            'params'      => [
                'category'  => 'Roots and Vegetables',
            ],
        ]);

        $this->interactionReceived([
            '_id'         => new ObjectID('56bd88a20374215a026fd789'),
            'author'      => 'shail@test.com',
            'interaction' => 'visited-product',
            'channel'     => 'mobile',
            'location'    => 'sorocaba',
            'params'      => [
                'productId' => 7,
                'category'  => 'Potato',
            ],
        ]);

        // When
        $this->runCommand('leadgen:proc-interaction');
        $this->waitElasticsearchOperations();

        // Then
        $this->customerShouldHaveInteraction(
            'steve_bowl@test.com',
            [
                '_id'         => new ObjectID('56bd88a20374215a026fd786'),
                'author'      => 'steve_bowl@test.com',
                'interaction' => 'visited-category',
                'channel'     => 'web',
                'location'    => 'rio_de_janeiro',
                'params'      => [
                    'category'  => 'Roots and Vegetables',
                ],
            ]
        );

        $this->customerShouldHaveInteraction(
            'shail@test.com',
            [
                '_id'         => new ObjectID('56bd88a20374215a026fd789'),
                'author'      => 'shail@test.com',
                'interaction' => 'visited-product',
                'channel'     => 'mobile',
                'location'    => 'sorocaba',
                'params'      => [
                    'productId' => 7,
                    'category'  => 'Potato',
                ],
            ]
        );
    }

    public function tearDown()
    {
        $this->cleanCollection('interactionTypes');
        $this->cleanCollection('interactions');
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

    protected function interactionReceived($interactionData)
    {
        $interaction = new Interaction;
        $interaction->fill($interactionData);
        $this->setProtected($interaction, 'writeConcern', 1);
        $interaction->save();
    }

    protected function customerShouldHaveInteraction($email, $interactionFields)
    {
        $customer = Customer::first(['email' => $email]);

        $contains = false;
        foreach ($customer->interactions() as $interaction) {
            $interactionIdentity = array_intersect_key($interaction->attributes, array_flip(array_keys($interactionFields)));

            if ($interactionFields == $interactionIdentity) {
                $contains = true;
                break;
            }
        }

        if (!$contains) {
            foreach ($customer->interactions() as $interaction) {
                $interactionAttributes[] = $interaction->attributes;
            }
            $this->assertContains($interactionFields, $interactionAttributes ?? []);
        }

        $this->assertTrue($contains);
    }
}
