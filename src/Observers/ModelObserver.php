<?php

namespace LoggiTrack\LoggiTrackSDKLaravel\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use LoggiTrack\LoggiTrackSDKLaravel\Helpers\SaveLogs;

class ModelObserver
{
    public function created($model)
    {
        // Get entity data
        $entityData = $model->toArray();

        // Get the current authenticated user
        $user = Auth::user();
        $userData = $user ? $user->toArray() : null;

        $logPayload  = [
            'type' => 'create',
            'model' => get_class($model),
            'entityData' => SaveLogs::removeEscapedFields((array)$entityData, config('loggitrack.escaped_fields')),
            'performedBy' => $userData,
            'performedAt' => now()->toDateTimeString(), // Log the time of the event
        ];

        if(config('loggitrack.queueable')){
            // dispatch job to save logs
        }else{
            SaveLogs::logModelUpdateEvent($logPayload);
        }

    }

    public function updated($model)
    {
        // Getting the current user who initiated the request (assuming you are using Laravel's default Auth)
        $currentUser = Auth::user();

        // Extracting request's basic details. Be cautious of NULL contexts (e.g., CLI, queued events)
        $requestDetails = [
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'origin' => Request::header('Origin'),
            'input' => Request::all(),
            'url' => Request::fullUrl(),
        ];

        // Fetching model's updated (dirty) and original data for logging
        $dirtyData = $model->getDirty();
        $originalData = $model->getOriginal();

        // You can also capture the entire current user, but for a log, usually, ID or a unique identifier is more than enough.
        $userId = optional($currentUser)->id; // This is null-safe; ensure your application's login logic stores a user.

        // Payload for log record
//        $logPayload = [
//            'performed_by' => [
//                'user_id' => $userId,
//                'user_name' => optional($currentUser)->name
//            ],
//            'model_entity' => get_class($model),
//            'original_data' => $originalData,
//            'updated_data' => $dirtyData,
//            'request_info' => $requestDetails,
//            'type' => 'updated',
//            // Additional event/log relevant stuff here
//        ];

        $logPayload  = [
            'type' => 'create',
            'model' => get_class($model),
            'entityData' => [
                'original' =>  SaveLogs::removeEscapedFields((array)$originalData, config('loggitrack.escaped_fields')),
                'updated_data' =>  SaveLogs::removeEscapedFields((array)$dirtyData, config('loggitrack.escaped_fields')),
            ],
            'performedBy' => $currentUser->id,
            'performedAt' => now()->toDateTimeString(), // Log the time of the event
        ];

        if(config('loggitrack.queueable')){
            // dispatch job to save logs

        }else{
            SaveLogs::logModelUpdateEvent($logPayload);
        }


    }

    public function deleted($model)
    {
        // Logic to log delete event
    }

    // Add other relevant model events you wish to observe and log
}

