<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Allows modern browsers to request data from other domains
 */
class Cors {

    /**
     * Handles the response by adding headers that enable CORS
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}
