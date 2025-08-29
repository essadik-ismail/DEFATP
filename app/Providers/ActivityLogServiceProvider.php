<?php

namespace App\Providers;

use App\Services\ActivityLogger;
use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ActivityLogger::class, function ($app) {
            return new ActivityLogger();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
