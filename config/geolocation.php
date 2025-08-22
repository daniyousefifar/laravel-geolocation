<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Geolocation Driver
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default geolocation driver you would like to use
    | for your application. This driver will be used by default when
    | resolving location data for an IP address.
    |
    | Supported Drivers: "maxmind", "iplocate", "null"
    |
    */

    'driver'          => env('GEOLOCATION_DRIVER', 'maxmind'),

    /*
    |--------------------------------------------------------------------------
    | Fallback Geolocation Driver
    |--------------------------------------------------------------------------
    |
    | If the default driver fails to resolve a location, you can specify a
    | fallback driver to be tried next. Set to `null` to disable.
    |
    */
    'fallback_driver' => env('GEOLOCATION_FALLBACK_DRIVER', null),

    /*
    |--------------------------------------------------------------------------
    | Geolocation Driver Configurations
    |--------------------------------------------------------------------------
    |
    | Here are each of the driver configurations for your application.
    | You can add your own drivers here as well, but make sure to
    | extend the manager in a service provider.
    |
    */

    'drivers' => [

        'iplocate' => [
            // API Access Key for the iplocate.io service.
            'key' => env('IPLOCATE_KEY'),
        ],

        'ipquery' => [
            // This service is free and does not require an API key,
        ],

        'ipapi' => [
            'key' => env('IPAPI_KEY'),
        ],

        'ipapico' => [
            'key' => env('IPAPICO_KEY'),
        ],

        'maxmind' => [
            // The absolute path to the MaxMind GeoLite2 or GeoIP2 database file.
            'database_path' => env('MAXMIND_DATABASE_PATH', __DIR__.'/../database/GeoLite2-City.mmdb'),
        ],

        /**
         * The Chain driver allows you to define a sequence of drivers to try.
         * It will attempt to use each driver in the order they are listed.
         * The first driver to successfully return a location will be used.
         * This is a powerful alternative to the simple 'fallback_driver'.
         */
        'chain'   => [
            'drivers' => [
                'iplocate', // First, try the fast API driver.
                'maxmind',  // If it fails, fall back to the local database.
            ],
        ],

        'null' => [
            // Predefined data for the "null" driver.
            'iso_code'         => 'ZZ',
            'country'          => 'Unknown',
            'city'             => null,
            'state'            => null,
            'postal_code'      => null,
            'lat'              => 0.0,
            'lon'              => 0.0,
            'timezone'         => 'UTC',
            'continent'        => 'Unknown',
            'currency'         => null,
            'asn_number'       => 0,
            'asn_organization' => 'Unknown',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | To increase performance and reduce API usage, you can enable caching
    | for resolved locations. All location data retrieved from any
    | driver will be stored in your application's default cache.
    |
    */

    'cache' => [
        'enabled' => env('GEOLOCATION_CACHE_ENABLED', false),
        'store'   => env('GEOLOCATION_CACHE_STORE', null),
        'ttl'     => env('GEOLOCATION_CACHE_TTL', 60 * 24), // 24 hours
        'prefix'  => 'geolocation_',
        'tag'     => 'geolocation',
    ],
];
