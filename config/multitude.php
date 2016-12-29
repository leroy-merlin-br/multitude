<?php

return [

    /**
     * Enables HTTPs. This is highly recommended in production environments.
     * Some routes will force the use of https protocol. This is handled by the
     * App\Http\Middleware\SecureConnection middleware.
     */
    'https' => env('HTTPS', false),
];
