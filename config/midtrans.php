<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Midtrans
    |--------------------------------------------------------------------------
    |
    | Payment gateway credentials and Snap settings. Read from environment.
    |
    */

    'merchant_id' => env('MIDTRANS_MERCHAT_ID'),

    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    'server_key' => env('MIDTRANS_SERVER_KEY'),

    'is_production' => filter_var(env('MIDTRANS_MERCHAT_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN),

    'is_sanitized' => filter_var(env('MIDTRANS_MERCHAT_SANITIZED', true), FILTER_VALIDATE_BOOLEAN),

    'is_3ds' => filter_var(env('MIDTRANS_MERCHAT_3DS', true), FILTER_VALIDATE_BOOLEAN),

    'uri' => env('MIDTRANS_URI_SANDBOX', 'https://app.sandbox.midtrans.com/snap/snap.js'),

];