<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenProvider API credentials
    |--------------------------------------------------------------------------
    | Register at https://rcp.openprovider.eu  (reseller panel)
    | Use CTE (test) environment first, then switch to production.
    */

    'username'  => env('OPENPROVIDER_USERNAME', ''),
    'password'  => env('OPENPROVIDER_PASSWORD', ''),

    // 'https://api.openprovider.eu/v1beta'  (production)
    // 'https://api.cte.openprovider.eu/v1beta'  (sandbox/test)
    'api_url'   => env('OPENPROVIDER_API_URL', 'https://api.openprovider.eu/v1beta'),

    'default_ns' => [
        env('OPENPROVIDER_NS1', 'ns1.barabdonline.xyz'),
        env('OPENPROVIDER_NS2', 'ns2.barabdonline.xyz'),
    ],
];
