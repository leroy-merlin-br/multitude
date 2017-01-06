<?php
namespace Infrastructure\SystemResources;

/**
 * Memory utilities
 */
class MemoryUtil
{
    /**
     * Get memory limit in bytes
     *
     * @return integer Memory limit in bytes.
     */
    public function getLimit(): int
    {
        $limitValue = ini_get('memory_limit');

        if ($limitValue < 0) {
            $limitValue = '4096M';
        }

        $last = strtolower($limitValue[strlen($limitValue)-1]);
        switch ($last) {
            case 'g':
                $limitValue *= 1024;
                // Fall through
            case 'm':
                $limitValue *= 1024;
                // Fall through
            case 'k':
                $limitValue *= 1024;
        }

        return $limitValue;
    }

    /**
     * Returns the current memory usage in bytes
     *
     * @return integer Memory usage.
     */
    public function getUsage(): int
    {
        return memory_get_usage();
    }

    /**
     * Returns the percentage of the memory that was used considering the limit
     * and the current usage in bytes.
     *
     * @return float Amount of memory used, where 0.5 means 50% of usage, for example.
     */
    public function getUsagePercentage(): float
    {
        return $this->getUsage() / $this->getLimit();
    }
}
