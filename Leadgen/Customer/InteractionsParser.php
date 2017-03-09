<?php

namespace Leadgen\Customer;

/**
 * A service that, by receiving a list of interactions, will embed then to the
 * correspondent customer (or create it) in order to make sure that the
 * interactions are "as close as possible" for later queries.
 */
class InteractionsParser
{
    /**
     * Repository of customer.
     *
     * @var Repository
     */
    protected $customerRepo;

    /**
     * Array of Customers that were modified by the InteractionsParser.
     *
     * @var array
     */
    protected $touchedCustomers = [];

    /**
     * Constructs and inject dependencies.
     *
     * @param Repository $customerRepo Customer repository instance.
     */
    public function __construct(Repository $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    /**
     * Parse a list of interactions, embeding then into Customers. After the
     * parsing, the customers that were affected (that have made those
     * interactions) will be returned.
     *
     * @param Interaction[] $interactions A list of `Interaction` objects.
     *
     * @return Customer[] An array contatining all the customers that have made the interactions that were parsed.
     */
    public function parse($interactions)
    {
        $customers = $this->getCustomersOfInteractions($interactions);

        foreach ($customers as $customer) {
            $embededInteractions = [];
            foreach ($interactions as $key => $interaction) {
                if ($interaction->authorId == $customer->_id) {
                    $customer->embed('interactions', $interaction);
                    $customer->interacted_at = $interaction->created_at;
                    $customer->location = $interaction->location ?: $customer->location;
                    $embededInteractions[] = $key;
                }
            }

            $this->touchedCustomers[$customer->_id] = $customer;
            $interactions = array_diff_key($interactions, $embededInteractions);

            // Free memory
            unset($embededInteractions);
            unset($customers);
        }

        if (count($interactions)) {
            $this->generateCustomersForInteractions($interactions);
        }

        $this->saveTouchedCustomers();

        return $this->touchedCustomers;
    }

    protected function getCustomersOfInteractions($interactions)
    {
        $customerIds = [];

        foreach ($interactions as $interaction) {
            $customerIds[] = $interaction->authorId;
        }

        $customerIds = array_values(array_unique($customerIds));

        return $this->customerRepo->where(['_id' => ['$in' => $customerIds]], 1, -1);
    }

    protected function generateCustomersForInteractions($interactions)
    {
        foreach ($interactions as $interaction) {
            if (isset($this->touchedCustomers[$interaction->authorId])) {
                $customer = $this->touchedCustomers[$interaction->authorId];
            } else {
                $customer = Customer::first($interaction->authorId) ?: new Customer();
                $customer->_id = $interaction->authorId;
                if (strstr($interaction->author, '@')) {
                    $customer->email = $interaction->author ?: null;
                } else {
                    $customer->docNumber = $interaction->author ?: null;
                }
            }

            $customer->embed('interactions', $interaction);
            $customer->interacted_at = $interaction->created_at;
            $customer->location = $interaction->location ?: $customer->location;
            $this->touchedCustomers[$customer->_id] = $customer;
        }
    }

    /**
     * Saves the customers that were touched by the parser
     * @return void
     */
    protected function saveTouchedCustomers()
    {
        foreach ($this->touchedCustomers as $customer) {
            try {
                $customer->save();
            } catch (\Exception $e) {
            }
        }
    }
}
