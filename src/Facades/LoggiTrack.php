<?php

namespace LoggiTrack\LoggiTrackSDKLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class LoggiTrack extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'loggitrack';
    }
}