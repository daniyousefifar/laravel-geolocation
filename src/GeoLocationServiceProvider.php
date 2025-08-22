<?php

namespace MyDaniel\GeoLocation;

use Illuminate\Support\ServiceProvider;
use MyDaniel\GeoLocation\Commands\ClearCacheCommand;
use MyDaniel\GeoLocation\Commands\LocateCommand;
use MyDaniel\GeoLocation\Contracts\ValidatorInterface;
use MyDaniel\GeoLocation\Validation\Validator;

/**
 * The service provider for the GeoLocation package.
 *
 * This class is responsible for bootstrapping the package into the Laravel
 * application. It registers the configuration, the main manager class in the
 * service container, and any artisan commands.
 */
class GeoLocationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This method binds the GeoLocationManager into the service container
     * as a singleton and merges the package configuration with the
     * application's configuration.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/geolocation.php', 'geolocation'
        );

        $this->app->bind(ValidatorInterface::class, Validator::class);

        $this->app->singleton('geolocation', function ($app) {
            return new GeoLocationManager(
                $app,
                $app->make(ValidatorInterface::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * This method makes the package's configuration and database files
     * publishable and registers the artisan commands when running
     * in the console.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/geolocation.php' => config_path('geolocation.php'),
        ], 'geolocation-config');

        $this->publishes([
            __DIR__.'/../database/GeoLite2-City.mmdb' => storage_path('app/geoip/GeoLite2-City.mmdb'),
        ], 'geolocation-database');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearCacheCommand::class,
                LocateCommand::class,
            ]);
        }
    }
}
