<?php

namespace App\Http;

use App\Http\Response\ApiResponse;
use Illuminate\Http\Response;

/**
 * Can be used to builds response objects.
 */
class ResponseBuilder
{
    /**
     * The status code that will be responded.
     *
     * @var int
     */
    protected $statusCode = 200;

    /**
     * The status message that will be visible in the response.
     *
     * @var string
     */
    protected $statusMessage = 'success';

    /**
     * Errors array that will be visible in the response.
     *
     * @var string
     */
    protected $errors = [];

    /**
     * Set the status code of the response.
     *
     * @param int    $statusCode Code of the response.
     * @param string $message    Status message that will be displayed along the response.
     *
     * @return this
     */
    public function setStatusCode(int $statusCode, string $message = null)
    {
        $this->statusCode = $statusCode;
        $this->statusMessage = $message ?? $this->generateErrorMessage($statusCode);

        return $this;
    }

    /**
     * Add a new error message to the list of errors that will be responded.
     *
     * @param string $errorMessage Error message to be displayed.
     *
     * @return this
     */
    public function addErrorMessage(string $errorMessage)
    {
        $this->errors[] = $errorMessage;

        return $this;
    }

    /**
     * Set all the error messages that will be responded.
     *
     * @param string[] $errorMessages Array of error messages.
     *
     * @return this
     */
    public function setErrorMessages(array $errorMessages)
    {
        $this->errors = $errorMessages;

        return $this;
    }

    /**
     * Return the response.
     *
     * @param mixed $data Data of the response.
     *
     * @return Response
     */
    public function respond($data = null): Response
    {
        $content = [
            'status'  => $this->statusMessage,
            'content' => $data,
            'errors'  => $this->errors,
        ];

        $response = app(ApiResponse::class);
        $response->setContent($content);
        $response->setStatusCode($this->statusCode);

        return $response;
    }

    /**
     * Return the response related to a resource not being found.
     *
     * @param mixed $data Data of the response.
     *
     * @return Response
     */
    public function respondNotFound($data = null): Response
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->addErrorMessage('Not found!')
            ->respond($data);
    }

    /**
     * Return the response related a validation error.
     *
     * @param mixed    $data   Data of the response.
     * @param string[] $errors Array of error messages.
     *
     * @return Response
     */
    public function respondBadRequest($data = null, array $errors = []): Response
    {
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->setErrorMessages($errors)
            ->respond($data);
    }

    /**
     * Returns the response telling that the request was not secure
     *
     * @param  mixed $data   Data of the respone.
     * @param  array $errors Error messages.
     *
     * @return Response
     */
    public function respondForbidden($data = null, array $errors = []): Response
    {
        return $this->setStatusCode(Response::HTTP_FORBIDDEN)
            ->setErrorMessages($errors)
            ->respond($data);
    }

    /**
     * Return a default response message for the given status code.
     *
     * @param int $statusCode Referent to the message.
     *
     * @return string The default message
     */
    protected function generateErrorMessage(int $statusCode): string
    {
        $errorMessages = [
            100 => 'continue',
            101 => 'switching protocols',
            102 => 'processing',
            200 => 'success',
            201 => 'created',
            202 => 'accepted',
            203 => 'non authoritative information',
            204 => 'no content',
            205 => 'reset content',
            206 => 'partial content',
            207 => 'multi status',
            208 => 'already reported',
            226 => 'im used',
            300 => 'multiple choices',
            301 => 'moved permanently',
            302 => 'found',
            303 => 'see other',
            304 => 'not modified',
            305 => 'use proxy',
            306 => 'reserved',
            307 => 'temporary redirect',
            308 => 'permanently redirect',
            400 => 'bad request',
            401 => 'unauthorized',
            402 => 'payment required',
            403 => 'forbidden',
            404 => 'not found',
            405 => 'method not allowed',
            406 => 'not acceptable',
            407 => 'proxy authentication required',
            408 => 'request timeout',
            409 => 'conflict',
            410 => 'gone',
            411 => 'length required',
            412 => 'precondition failed',
            413 => 'request entity too large',
            414 => 'request uri too long',
            415 => 'unsupported media type',
            416 => 'requested range not satisfiable',
            417 => 'expectation failed',
            418 => 'i am a teapot',
            421 => 'misdirected request',
            422 => 'unprocessable entity',
            423 => 'locked',
            424 => 'failed dependency',
            425 => 'reserved for webdav advanced collections expired proposal',
            426 => 'upgrade required',
            428 => 'precondition required',
            429 => 'too many requests',
            431 => 'request header fields too large',
            451 => 'unavailable for legal reasons',
            500 => 'internal server error',
            501 => 'not implemented',
            502 => 'bad gateway',
            503 => 'service unavailable',
            504 => 'gateway timeout',
            505 => 'version not supported',
            506 => 'variant also negotiates experimental',
            507 => 'insufficient storage',
            508 => 'loop detected',
            510 => 'not extended',
            511 => 'network authentication required',
        ];

        return $errorMessages[$statusCode] ?? 'unknown';
    }
}
