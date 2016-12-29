<?php

namespace App\Http\Middleware;

use App\Exceptions\NonSecureRequestException;
use Closure;
use Illuminate\Http\Request;

/**
 * Class SecureConnection.
 *
 * Check if the request is using a secure connection (HTTPS), if not, redirect
 * to itself using https.
 */
class SecureConnection
{
    /**
     * Handle the request.
     *
     * @see  http://symfony.com/doc/current/cookbook/request/load_balancer_reverse_proxy.html
     *
     * @param Request $request Incoming request.
     * @param Closure $next    Next closure.
     *
     * @throws NonSecureRequestException If the request was not secure.
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Exists if feature.https is disabled
        if (! config('multitude.https', false)) {
            return $next($request);
        }

        // Sets the IP coming of "x-forwarded-for" as a trusted proxy
        $request->setTrustedProxies([$request->server->get('REMOTE_ADDR')]);

        // Tests if the request is secure, if not, redirects to the secured version
        if (!$request->secure()) {
            throw new NonSecureRequestException;
        }

        return $next($request);
    }
}
