<?php

namespace Leadgen\Base\SchemaFields;

/**
 * This trait is meant to be used in classes that extend Mongolid\Schema.
 *
 * Implements the `cron` schema field.
 */
trait CronFieldTrait
{
    /**
     * Prepares a field to be a cron string or null.
     *
     * @param mixed $value Value that will be evaluated.
     *
     * @return string
     */
    public function cron($value = '0 0 * * * *')
    {
        $value = trim($value);
        $cronPattern = '/^(\S+) (\S+) (\S+) (\S+) (\S+) (\S+)$/';

        if (preg_match($cronPattern, $value)) {
            return $value;
        };

        return '0 0 * * * *';
    }
}
