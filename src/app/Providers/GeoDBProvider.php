<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GeoDBProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerCommands();
        $this->mergeConfigFrom(
            __DIR__ . '/../config/geodb.php', 'geodb'
        );

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishMigrations();
        $this->publishSeeds();
        $this->publishTranslations();
    }

    /**
     * Publish migration files.
     */
    private function publishMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    /**
     * Publish seeder files.
     */
    private function publishSeeds()
    {
        $this->publishes([__DIR__ . '/../database/seeders/' => base_path('database/seeders')], 'seeders');
    }

    /**
     * Publish API routes
     */
    private function publishRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . "/../../routes/api.php");
        $this->loadRoutesFrom(__DIR__ . "/../../routes/web.php");
    }

    private function publishViews()
    {
        $this->loadViewsFrom(__DIR__ . "/../../resources/views", "geodb");
    }

    /**
     * Register commands
     */
    private function registerCommands()
    {
        $this->commands([
            \App\Console\geoDBInstall::class,
        ]);
    }

    /**
     * Publishes translation files
     */
    private function publishTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'geodb');
        $this->publishes([
            __DIR__ . '/../lang' => $this->app->langPath('vendor/geodb')
        ]);
    }
}
