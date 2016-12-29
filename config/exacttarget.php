<?php

return [
    /*
     |-------------------------------------------------------------------------
     | Authentication
     |-------------------------------------------------------------------------
     | API keys to integrate with Exacttarget.
     | Consists is a public access token and a secret token.
     |-------------------------------------------------------------------------
     */

    'clientId' => env('EXACTTARGET_CLIENT_ID'),
    'clientSecret' => env('EXACTTARGET_CLIENT_SECRET'),
];
