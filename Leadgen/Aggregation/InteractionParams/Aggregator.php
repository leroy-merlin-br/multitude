<?php
namespace Leadgen\Aggregation\InteractionParams;

use Iterator;

/**
 * Aggregates the fields of a series of Interactions.
 */
class Aggregator
{
    /**
     * If the aggregation for one of the $fields exceeds this amount,
     * the aggregation will stop for that field.
     */
    const ENOUGH_VALUES = 20;

    /**
     * Runs an aggregation of the given params of the given interactions.
     *
     * @param  array|\Iterator $interactions Interactions that will be aggregated.
     * @param  array           $fields       Interaction fields that should be aggregated.
     *
     * @throws \InvalidArgumentException If the $resources is not an array of iterable.
     *
     * @return array The $fields will be the keys witht he aggregated results as values.
     */
    public function aggregate($interactions, array $fields)
    {
        if (!(is_array($interactions) || $interactions instanceof Iterator)) {
            throw new \InvalidArgumentException('$interactions should be iterable, invalid type given.');
        }

        $result = [];
        foreach ($fields as $fieldName) {
            $result[$fieldName] = [];
        }

        foreach ($interactions as $interaction) {
            foreach ($fields as $i => $fieldName) {
                if ($paramValue = $interaction->params[$fieldName] ?? null) {
                    if (is_array($paramValue)) {
                        $result[$fieldName] = array_merge($result[$fieldName], $paramValue);
                    } else {
                        array_push($result[$fieldName], $paramValue);
                    }
                }

                if (count($result[$fieldName]) > static::ENOUGH_VALUES) {
                    unset($fields[$i]);
                }
            }
        }

        foreach ($result as $key => $values) {
            $result[$key] = array_values(array_unique($values));
        }

        return $result;
    }
}
