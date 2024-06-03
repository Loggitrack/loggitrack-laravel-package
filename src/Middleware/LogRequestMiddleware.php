<?php

namespace LoggiTrack\LoggiTrackSDKLaravel\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LogiTrack\LogiTrackSDKLaravel\Helpers\SaveLogs;
use Symfony\Component\HttpFoundation\Response;
class LogRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $request->start_time = microtime(true) * 1000;

        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        $responseTime = (microtime(true) * 1000) - $request->start_time;
        $requestData = [
            "timestamp" => now()->toDateTimeString(), // Get current timestamp
            "method" => request()->getMethod(),
            'apiversion' => env('API_VERSION'),
            "application_name" => env('APP_NAME'),
            "clienttype" => 'API',
            "environment" => env('APP_ENV'),
            "url" => request()->url(),
            "protocol" => request()->getProtocolVersion(),
            "headers" => SaveLogs::removeEscapedFields(json_decode(json_encode(request()->headers->all()), true), config('loggitrack.escaped_fields')),
            "body" => SaveLogs::removeEscapedFields(json_decode(request()->getContent(), true), config('loggitrack.escaped_fields')),
            "query_parameters" => (string)json_encode(request()->query->all()),
            "remote_address" => request()->ip(),
            "user_id" => request()->user()->id ?? null, // Get user ID if authenticated
            "request_id" => request()->header('X-Request-ID') ?? null, // Check for unique request ID
            "referrer" => request()->header('Referer') ?? null,
            "cookies" => count(request()->cookies->all()) > 0 ? json_decode(json_encode(request()->cookies->all())) : null,
            "content_length" => (int)request()->header('Content-Length') ?? 0,
            "origin" => request()->header('Origin') ?? null,
            "is_secure" => request()->isSecure(),
            // Add other fields as needed, accessing them from request object
            "auth_token" => request()->header('Authorization') ?? null,  // Extract auth token from header
            "response_time" => $responseTime, // Convert to milliseconds
            "response_status_code" => $response->getStatusCode(),
            "response_data" => SaveLogs::removeEscapedFields(json_decode(request()->getContent(), true), config('loggitrack.escaped_fields')),
        ];


        SaveLogs::logRequestEvent($requestData);


        // Store the session data...
    }
}