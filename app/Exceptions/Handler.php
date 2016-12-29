<?php

namespace App\Exceptions;

use App\Exceptions\NonSecureRequestException;
use App\Http\ResponseBuilder;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Mongolid\Exception\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles errors and exceptions in the system
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        switch (true) {
            // In case of not found
            case $e instanceof ModelNotFoundException:
            case $e instanceof NotFoundHttpException:
                if ($this->shouldRespondJson($request)) {
                    return app(ResponseBuilder::class)->respondNotFound();
                }
                return new Response(view('errors.404'), 404);

            // In case of no secure
            case $e instanceof NonSecureRequestException:
                if ($this->shouldRespondJson($request)) {
                    return app(ResponseBuilder::class)->respondForbidden(null, [$e->getMessage()]);
                }
                return new RedirectResponse($this->getSecureVersion($request->fullUrl()));
        }

        return parent::render($request, $e);
    }

    /**
     * Returns the secure version of the given URL
     * @param  string $url Url.
     * @return string
     */
    protected function getSecureVersion($url)
    {
        return str_replace(':8000/', ':8443/', str_replace('http:', 'https:', $url));
    }

    /**
     * Tells whenever a request should be responded with json.
     *
     * @param  Request $request
     *
     * @return boolean
     */
    protected function shouldRespondJson(Request $request)
    {
        return in_array('application/json', $request->getAcceptableContentTypes()) ||
            strstr($request->getRequestUri(), '/api/');
    }
}
