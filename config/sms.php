<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel sms notifications
    |--------------------------------------------------------------------------
    */
    'default' => env('SMS_PROVIDER', 'smscru'),

    'providers' => [
        'smsru' => [
            'api_id' => env('SMSRU_API_ID'),
        ],
        'smscru' => [
            'login' => env('SMSCRU_LOGIN'),
            'password' => env('SMSCRU_PASSWORD'),
            'sender' => env('SMSCRU_SENDER')
        ]
    ]
];
