<?php
namespace Leadgen\Aggregation\InteractionParams;

use Iterator;

/**
 * Aggregator Trigger to be Ignited from a Trigger entity.
 */
class AggregatorTrigger
{
    /**
     * @var Aggregator
     */
    protected $aggregator;

    /**
     * Injects dependencies
     * @param Aggregator $aggregator Aggregator instance.
     */
    public function __construct(Aggregator $aggregator)
    {
        $this->aggregator = $aggregator;
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
        if (!(is_array($customers) || $customers instanceof Iterator)) {
            throw new \InvalidArgumentException('$customers should be iterable, invalid type given.');
        }

        if (! $fields = $settings['fields'] ?? null) {
            return;
        }

        foreach ($customers as $customer) {
            $customer->aggregated = $this->aggregator->aggregate($customer->interactions(), $fields);
            $customer->update();
        }

        return true;
    }
}
