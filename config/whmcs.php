<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WHMCS API Configuration
    |--------------------------------------------------------------------------
    */

    'api_url'    => env('WHMCS_API_URL', ''),       // e.g. https://billing.barabdonline.xyz/includes/api.php
    'identifier' => env('WHMCS_API_IDENTIFIER', ''), // API Credential Identifier
    'secret'     => env('WHMCS_API_SECRET', ''),     // API Credential Secret
    'access_key' => env('WHMCS_API_ACCESS_KEY', ''), // Optional access key (if configured)
    'admin_user' => env('WHMCS_ADMIN_USER', ''),     // Admin username (for some legacy actions)

    /*
    |--------------------------------------------------------------------------
    | Webhook secret – set a random string here and configure the same
    | value inside WHMCS hooks so we can verify incoming requests.
    |--------------------------------------------------------------------------
    */
    'webhook_secret' => env('WHMCS_WEBHOOK_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Default payment method name in WHMCS for SSLCommerz payments.
    |--------------------------------------------------------------------------
    */
    'payment_method' => env('WHMCS_PAYMENT_METHOD', 'sslcommerz'),

    /*
    |--------------------------------------------------------------------------
    | Billing cycle key mapping – Laravel key → WHMCS accepted value.
    | WHMCS expects specific cycle names: monthly, quarterly, semiannually,
    | annually, biennially, triennially, free.
    |--------------------------------------------------------------------------
    */
    'cycle_map' => [
        'monthly'       => 'monthly',
        'quarterly'     => 'quarterly',
        'semi-annually' => 'semiannually',
        'semi_annually' => 'semiannually',
        'semiannually'  => 'semiannually',
        'annually'      => 'annually',
        'yearly'        => 'annually',
        'biennially'    => 'biennially',
        'triennially'   => 'triennially',
    ],

];
