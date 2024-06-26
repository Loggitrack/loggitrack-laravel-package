<?php

namespace LoggiTrack\LoggiTrackSDKLaravel;

use Illuminate\Support\ServiceProvider;
use LoggiTrack\LoggiTrackSDKLaravel\Observers\ModelObserver;

class LoggitrackServiceProvider extends ServiceProvider
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
            return new LoggiTrackService();
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