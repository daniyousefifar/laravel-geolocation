<?php

namespace MyDaniel\GeoLocation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * The facade for the GeoLocation service.
 *
 * This class provides a static-like interface to the methods available
 * on the GeoLocationManager class, allowing for an expressive and
 * convenient way to access the service throughout a Laravel application.
 *
 * @method static \MyDaniel\GeoLocation\Location locate(string $ip) Locate the geographical information for a given IP address.
 * @method static \MyDaniel\GeoLocation\Contracts\GeoLocationDriver driver(string|null $driver = null) Get a specific geolocation driver instance.
 * @see \MyDaniel\GeoLocation\GeoLocationManager
 */
class GeoLocation extends Facade
{
    /**
     * Get the registered name of the component in the service container.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'geolocation';
    }
}
