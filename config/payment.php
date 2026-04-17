<?php

return [
    'request_time_out_in_seconds' => env('PAYMENT_GATEWAY_REQUEST_TIME_OUT_IN_SECONDS', 120),
    'return_url' => env('PAYMENT_GATEWAY_RETURN_URL'),

    'gateways' => [
        'clickpay' => [
            'server_key' => env('CLICKPAY_SERVER_KEY', ''),
            'profile_id' => env('CLICKPAY_PROFILE_ID', ''),
            'due_date_in_minutes' => env('CLICKPAY_DUE_DATE_IN_MINUTES', 30),
            'base_url' => env('CLICKPAY_BASE_URL', 'https://secure.clickpay.com.sa'),
        ],
        'moyasar' => [
            'api_key' => env('MOYASAR_API_KEY', ''),
            'due_date_in_minutes' => env('MOYASAR_DUE_DATE_IN_MINUTES', 30),
            'base_url' => env('MOYASAR_BASE_URL', 'https://api.moyasar.com/v1'),
        ],
        'pay_tabs' => [
            'server_key' => env('PAYTABS_SERVER_KEY', ''),
            'profile_id' => env('PAYTABS_PROFILE_ID', ''),
            'due_date_in_minutes' => env('PAYTABS_DUE_DATE_IN_MINUTES', 30),
            'base_url' => env('PAYTABS_BASE_URL', 'https://secure.paytabs.sa'),
        ],
    ],
];
