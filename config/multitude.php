<?php

return [

    /**
     * Enables HTTPs. This is highly recommended in production environments.
     * Some routes will force the use of https protocol. This is handled by the
     * App\Http\Middleware\SecureConnection middleware.
     */
    'https' => env('HTTPS', false),

    /**
     * Enabled AuthToken requirement. This must be enabled in any deployment of
     * multitude. It should be disabled for testing only. This is handled by the
     * App\Http\Middleware\AuthToken middleware.
     */
    'auth_token' => env('AUTH_TOKEN_ENABLED', true),
];
