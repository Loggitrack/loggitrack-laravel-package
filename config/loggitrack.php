<?php

return [
    'api_key' => env('LOGGITRACK_API_KEY', ''),
    'api_url' => env('LOGGITRACK_API_URL', 'http://127.0.0.1:3020'),
    'observed_models' => [
        // Example: App\Models\User::class,
    ],
    // Additional configuration options
    'queueable' => false,

    /**
     * the request logger and model logger will not save these fields.
     */
    'escaped_fields' => [
        'password',
    ]
];
