<?php

namespace LogiTrack\LogiTrackSDKLaravel;

use Illuminate\Support\ServiceProvider;
use LogiTrack\LogiTrackSDKLaravel\Observers\ModelObserver;

class LogitrackServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/loggitrack.php' => config_path('loggitrack.php'),
        ]);
        $this->registerModelObservers();
    }

    public function register()
    {
        $this->app->singleton('loggitrack', function ($app) {
            return new LogiTrackService();
        });
    }

    protected function registerModelObservers()
    {
        $observedModels = config('loggitrack.observed_models', []);
        foreach ($observedModels as $model) {
            $model::observe(ModelObserver::class);
        }
    }
}