<?php

return [
    'api_key' => env('LOGITRACK_API_KEY', ''),
    'api_url' => env('LOGITRACK_API_URL', ''),
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
