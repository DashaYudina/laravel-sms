<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel sms notifications
    |--------------------------------------------------------------------------
    */
    'default' => env('SMS_PROVIDER', 'smsru'),

    'providers' => [
        'smsru' => [
            'api_id' => env('SMSRU_API_ID'),
            'url' => env('SMSRU_URL')
        ],
        'smscru' => [
            'login' => env('SMSCRU_LOGIN'),
            'password' => env('SMSCRU_PASSWORD'),
            'url' => env('SMSCRU_URL'),
            'sender' => env('SMSCRU_SENDER')
        ]
    ]
];
