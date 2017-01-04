<?php
namespace Leadgen\ExactTarget;

use Leadgen\Customer\Customer;
use LeroyMerlin\ExactTarget\Client;
use LeroyMerlin\ExactTarget\Exception\ExactTargetClientException;
use Psr\Log\LoggerInterface;
use Iterator;

/**
 * Updates customers into ExactTarget's data extensions.
 */
class CustomerUpdater
{
    /**
     * ExactTarget Client
     * @var Client
     */
    protected $exacttarget;

    /**
     * @var LoggerInterface
     */
    protected $log;

    /**
     * Injects dependencies
     * @param Client          $exacttarget ExactTarget client.
     * @param LoggerInterface $log         Log writter instance.
     */
    public function __construct(Client $exacttarget, LoggerInterface $log)
    {
        $this->exacttarget = $exacttarget;
        $this->log = $log;
    }

    /**
     * Send the given Customers to the given ExactTarget's data extension
     *
     * @param  mixed    $customers     Customers that will be sent to ExactTarget.
     * @param  string   $dataExtension Identifier of the data extension.
     * @param  string[] $fields        Fields to map to the data extension. [<entityField> => <dataExtensionField>].
     *
     * @throws \InvalidArgumentException If the $resources is not an array of iterable.
     *
     * @return bool Success.
     */
    public function send($customers, string $dataExtension, array $fields = [])
    {
        $preparedCustomers = $this->prepareCustomers($customers, $fields);

        return $this->commitToDataExtension($dataExtension, $preparedCustomers);
    }

    /**
     * Prepare the given array|iterabe given to the data format of ExactTarget
     * API.
     *
     * @param  mixed    $resources Customers that will be sent to ExactTarget.
     * @param  string[] $fields    Fields to map to the data extension. [<entityField> => <dataExtensionField>].
     *
     * @throws \InvalidArgumentException If the $resources is not an array of iterable.
     *
     * @return array Prepared data.
     */
    protected function prepareCustomers($resources, array $fields)
    {
        if (!(is_array($resources) || $resources instanceof Iterator)) {
            throw new \InvalidArgumentException('$resources should be iterable, invalid type given.');
        }

        $customerList = [];

        foreach ($resources as $customer) {
            if ($customer->isValid()) {
                $customerData = [
                    'keys' => [
                        'Email' => $customer->email,
                    ],
                    'values' => [
                        'Email' => $customer->email,
                    ]
                ];

                foreach ($fields as $key => $value) {
                    $fieldValue = array_get($customer->attributes, $key);
                    if ($fieldValue) {
                        $fieldValue = implode(';', (array)$fieldValue);
                        $customerData['values'][$value] = $fieldValue;
                    }
                }

                $customerList[] = $customerData;
            }
        }

        return $customerList;
    }

    /**
     * Commits the given parameters to data extension.
     *
     * @param string $dataExtension Data extension or "key" name.
     * @param array  $data          Data that will be sent.
     *
     * @return bool Success (without any critical error message).
     */
    protected function commitToDataExtension(string $dataExtension, array $data)
    {
        $parameters = [
            'key' => $dataExtension,
            'data' => $data
        ];

        try {
            $this->exacttarget->addDataExtensionRow($parameters);
        } catch (ExactTargetClientException $error) {
            $this->log->error(
                sprintf('[%s] Failed to sync with ExactTarget with message: "%s"', static::class, $error->getMessage())
            );

            if ($this->isErrorMessageCritical($error->getMessage())) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if the given exactTarget error message is critical.
     *
     * @param string $message Error message.
     *
     * @return bool is critical?
     */
    protected function isErrorMessageCritical(string $message)
    {
        // If the error message contain any of these, than it is not critical
        $nonCriticalErrors = [
            'InvalidEmailAddress',
            'exceeds the column\'s maximum length',
        ];

        foreach ($nonCriticalErrors as $error) {
            if (strstr($message, $error)) {
                return false;
            }
        }

        return true;
    }
}
