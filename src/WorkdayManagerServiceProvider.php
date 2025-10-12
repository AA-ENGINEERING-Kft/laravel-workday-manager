<?php

declare(strict_types=1);

namespace AAEngineering\WorkdayManager;

use Illuminate\Support\ServiceProvider;

final class WorkdayManagerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/workday-manager.php',
            'workday-manager'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/workday-manager.php' => config_path('workday-manager.php'),
        ], 'workday-manager-config');

        // Load migrations if enabled
        if (config('workday-manager.load_migrations', true)) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'workday-manager-migrations');
    }
}
