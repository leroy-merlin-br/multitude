<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * This Exception means that a Request should have been secure (HTTPS).
 */
class NonSecureRequestException extends HttpException
{
    public function __construct(
        $statusCode = 426,
        $message = "The request must use a secure protocol (HTTPS)",
        \Exception $previous = null,
        array $headers = array(),
        $code = 0
    ) {
        $headers['Connection'] = 'Upgrade';
        $headers['Upgrade'] = 'HTTPS/1.1';

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
