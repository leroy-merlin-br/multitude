<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class BasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (! $this->isAuthorized()) {
            return abort(
                401,
                'Unauthorized access',
                [
                    'WWW-Authenticate' => 'Basic realm="My Realm"',
                ]
            );
        }

        return $next($request);
    }

    protected function isAuthorized()
    {
        return array_get($_SERVER, 'PHP_AUTH_USER') === 'multitude'
               && array_get($_SERVER, 'PHP_AUTH_PW') === 'TalentBooster2016';
    }
}
