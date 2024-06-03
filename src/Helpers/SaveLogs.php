<?php

namespace LoggiTrack\LoggiTrackSDKLaravel\Helpers;

use Illuminate\Support\Facades\Http;

class SaveLogs
{

    public static function logModelUpdateEvent($logPayload) {
        $loggingServiceEndpoint = config('loggitrack.api_url').'/model-change';
        try {
            // Asynchronously sending the payload to the event logging service.
            // Timeout and retry options enhance the service's performance.
            $response = Http
//                ->retry(3, 100) // Retry 3 times with 100ms intervals if it fails.
                ::withHeaders([
                    'api_key' => config('loggitrack.api_key')
                ])->post($loggingServiceEndpoint, $logPayload);

            // Considerations: Do you want to monitor the status, log if it fails, or more?
            if ($response->failed()) {
                // Maybe a service like Laravel Log, or queueing an admin service report.
                \Log::error('LogModelUpdateEvent failed to send payload', ['response' => $response->body()]);
            }
        } catch (\Exception $exception) {
            // Here, you could log the failure reason if the logging is issue-based.
            \Log::error('Failed to send payload to event logging service', ['error' => $exception->getMessage(), 'payload' => $logPayload]);
        }
    }

    public static function logRequestEvent($logPayload) {
        $loggingServiceEndpoint = config('loggitrack.api_url').'/request-log';
        try {
            // Asynchronously sending the payload to the event logging service.
            // Timeout and retry options enhance the service's performance.
            $response = Http
//                ->retry(3, 100) // Retry 3 times with 100ms intervals if it fails.
                ::withHeaders([
                    'api_key' => config('loggitrack.api_key')
                ])->post($loggingServiceEndpoint, $logPayload);

            // Considerations: Do you want to monitor the status, log if it fails, or more?
            if ($response->failed()) {
                // Maybe a service like Laravel Log, or queueing an admin service report.
                \Log::error('LogRequestMiddleware failed to send payload', ['response' => $response->body()]);
            }
        } catch (\Exception $exception) {
            // Here, you could log the failure reason if the logging is issue-based.
            \Log::error('Failed to send payload to event logging service', ['error' => $exception->getMessage(), 'payload' => $logPayload]);
        }
    }


    public static function removeEscapedFields(array $content, array $escaped_fileds)
    {
        foreach ($escaped_fileds as $key) {
            if (array_key_exists($key, $content)) {
                unset($content[$key]);
            }
        }

        return $content;
    }

}