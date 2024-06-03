<?php

namespace LogiTrack\LogiTrackSDKLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class LoggiTrack extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'loggitrack';
    }
}