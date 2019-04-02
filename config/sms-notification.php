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
        ]
    ]
];
