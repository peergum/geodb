<?php

namespace Peergum\GeoDB\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Peergum\GeoDB\Console\Commands\GeoDBDownload;
use Peergum\GeoDB\Console\Commands\GeoDBInstall;

class GeoDBProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerCommands();
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/geodb.php', 'geodb'
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
        $this->publishRoutes();
        $this->publishViews();
        $this->publishAssets();
        AboutCommand::add('Laravel GeoDB', fn() => ['Version' => GeoDBInstall::VERSION]);

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
        $this->publishes([__DIR__ . '/../../database/seeders/' => base_path('database/seeders')], 'seeders');
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
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/geodb'),
        ]);

    }

    private function publishAssets()
    {
        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/geodb'),
        ], 'laravel-assets');
    }

    /**
     * Register commands
     */
    private function registerCommands()
    {
        $this->commands([
            GeoDBInstall::class,
            GeoDBDownload::class,
        ]);
    }

    /**
     * Publishes translation files
     */
    private function publishTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'geodb');
        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/geodb')
        ]);
    }
}
