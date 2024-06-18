
# Loggitrack Laravel SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/loggitrack/loggitrack-laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/loggitrack/loggitrack-laravel-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/loggitrack/loggitrack-laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/loggitrack/loggitrack-laravel-sdk)

The Loggitrack Laravel SDK provides seamless integration of the Loggitrack API into your Laravel application, allowing you to track API requests and responses, analyze performance metrics, and monitor user behavior.

## Installation

### Prerequisites

- PHP ^8.2
- Laravel ^8.0|^9.0|^10.0

### Installation Steps

1. Require the SDK via Composer:
   ```bash
   composer require loggitack/loggitrack-sdk-laravel
   ```

2. Publish the configuration file:
   ```bash
   php artisan vendor:publish --provider="Loggitrack\Laravel\Providers\LoggitrackServiceProvider"
   ```

3. Configure the SDK:
   Open the published configuration file `config/loggitrack.php` and update the settings as needed:

   ```php
   <?php

   return [
       'api_key' => env('LOGGITRACK_API_KEY', ''),
       'api_url' => env('LOGGITRACK_API_URL', ''),
       'observed_models' => [
           // Example: App\Models\User::class,
       ],
       // Additional configuration options
       'queueable' => false,
        /**
        * the request logger and model logger will not save these fields.
        */
        'escaped_fields' => [
        // 'password', 'api_key'
        ]
   ];
   ```

   Set the `LOGGITRACK_API_KEY` and `LOGGITRACK_API_URL` environment variables in your `.env` file:

   ```
   LOGGITRACK_API_KEY=your-api-key
   LOGGITRACK_API_URL=http://127.0.0.1:3020
   ```

## Usage

### Middleware

The SDK includes a `LogRequestMiddleware` middleware that should be added to each route you want to observe. This middleware will log the requests and responses for the specified endpoints.

1. Register the Middleware:
   Open the `app/Http/Kernel.php` file and add the middleware to the route middleware array:

   ```php
   protected $routeMiddleware = [
       // Other middleware
       'loggitrack' => \Loggitrack\Laravel\Http\Middleware\LogRequestMiddleware::class,
   ];
   ```

2. Apply the Middleware to Routes:
   Apply the `loggitrack` middleware to the routes you want to observe. For example:

   ```php
   Route::middleware(['loggitrack'])->group(function () {
       Route::get('/users', 'UserController@index');
       Route::post('/users', 'UserController@store');
       // Add other routes as needed
   });
   ```

### Example Usage

1. Set Up Observed Models:
   Add the models you want to observe to the `observed_models` array in the configuration file:

   ```php
   'observed_models' => [
       App\Models\User::class,
       App\Models\Order::class,
   ],
   ```

2. Send API Requests:
   The SDK will automatically log the requests and responses for the observed endpoints. You can use your application as usual, and the logs will be sent to the Loggitrack API for analysis.

### Additional Configuration Options

The SDK provides additional configuration options that can be customized in the `config/loggitrack.php` file:

- **queueable**: Set to `true` to queue the logging requests for asynchronous processing. Defaults to `false`.

## Troubleshooting

If you encounter any issues during the installation or usage of the Loggitrack Laravel SDK, refer to the following steps:

1. Ensure all prerequisites are met.
2. Verify your configuration settings in `config/loggitrack.php`.
3. Check the Laravel logs for any errors related to the SDK.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue to contribute.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
