<?php
namespace Leadgen\ExactTarget;

/**
 * ExactTarget Trigger to be Ignited from a Trigger entity
 */
class ExactTargetTrigger
{
    /**
     * @var CustomerUpdater
     */
    protected $customerUpdater;

    /**
     * Injects dependencies
     * @param CustomerUpdater $customerUpdater Customer updater instance.
     */
    public function __construct(CustomerUpdater $customerUpdater)
    {
        $this->customerUpdater = $customerUpdater;
    }

    /**
     * Trigger entry point
     *
     * @param  mixed $customers Array or iterator of Customer objects.
     * @param  array $settings  Associative array of settings.
     *
     * @throws \InvalidArgumentException If the $resources is not an array of iterable.
     *
     * @return mixed Results
     */
    public function fireTrigger($customers, array $settings)
    {
        return $this->customerUpdater->send(
            $customers,
            $settings['dataExtension'] ?? 'multitudeCustomers',
            $settings['fields'] ?? []
        );
    }
}
