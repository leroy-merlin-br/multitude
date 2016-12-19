<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Lumen\Http\ResponseFactory;

/**
 * Allows modern browsers to request data from other domains.
 */
class Cors
{
    /**
     * Handles the response by adding headers that enable CORS.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'  => $request->header('Origin', '*'),
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers' => 'Content-Type, Accept, Authorization, X-Requested-With',
        ];

        // Using this you don't need an method for 'OPTIONS' on controller
        if ($request->isMethod('OPTIONS')) {
            return app(ResponseFactory::class)->json(null, 200, $headers);
        }

        // For all other cases
        $response = $next($request);
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }
}
