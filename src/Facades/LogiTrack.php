<?php

namespace LogiTrack\LogiTrackSDKLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class LogiTrack extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'logitrack';
    }
}