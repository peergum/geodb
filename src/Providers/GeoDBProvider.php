<?php

namespace Peergum\GeoDB\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
//use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
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
        $this->publishAssets();
        $this->publishViews();
        $this->publishRoutes();
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
        copy(__DIR__.'/../../routes/geodb.php', base_path('routes/geodb.php'));
        copy(__DIR__.'/../../routes/geodb-api.php', base_path('routes/geodb-api.php'));

        $this->loadRoutesFrom(__DIR__ . "/../../routes/geodb-api.php");
        $this->loadRoutesFrom(__DIR__ . "/../../routes/geodb.php");

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/geodb-api.php'));

            Route::middleware('web')
                ->group(base_path('routes/geodb.php'));
        });

    }

    private function publishViews()
    {
        $this->loadViewsFrom(__DIR__ . "/../../resources/views", "geodb");
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/geodb'),
        ]);

        $this->publishVueComponents();
    }

    private function publishVueComponents()
    {
        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components/GeoDB'));
//        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages/GeoDB'));

        (new Filesystem)->copyDirectory(__DIR__ . '/../../resources/js/Components', resource_path('js/Components/GeoDB'));
        (new Filesystem)->copyDirectory(__DIR__ . '/../../resources/js/Pages', resource_path('js/Pages/GeoDB'));
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
        ]);
    }

    /**
     * Publishes translation files
     */
    private function publishTranslations()
    {
//        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'geodb');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../../lang');
        $this->publishes([
            __DIR__ . '/../../lang' => $this->app->langPath('vendor/geodb')
        ]);
    }
}
