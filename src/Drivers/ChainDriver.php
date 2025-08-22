<?php

namespace MyDaniel\GeoLocation\Drivers;

use MyDaniel\GeoLocation\Exceptions\LocationNotFoundException;
use MyDaniel\GeoLocation\GeoLocationManager;
use MyDaniel\GeoLocation\Location;
use Throwable;

/**
 * A driver that attempts to locate an IP address by chaining multiple drivers.
 *
 * This driver iterates through a predefined list of other drivers, calling them
 * in order until one of them successfully returns a location. If all drivers
 * in the chain fail, it throws an exception summarizing the errors. This is
 * useful for creating resilient geolocation lookups with built-in fallbacks.
 */
class ChainDriver extends AbstractDriver
{
    /**
     * The GeoLocation manager instance, used to resolve sub-drivers.
     *
     * @var GeoLocationManager
     */
    protected GeoLocationManager $manager;

    /**
     * ChainDriver constructor.
     *
     * @param  array<string, mixed>  $config  The configuration for this driver, expecting a 'drivers' key.
     * @param  GeoLocationManager  $manager  The main manager instance to resolve other drivers.
     */
    public function __construct(array $config, GeoLocationManager $manager)
    {
        parent::__construct($config);

        $this->manager = $manager;
    }

    /**
     * Locate the specified IP address by trying a sequence of drivers.
     *
     * It will attempt to use each driver specified in the configuration's 'drivers'
     *  array in order. The first one to return a successful result will be used.
     *
     * @param  string  $ip  The IP address to locate.
     *
     * @return Location The location data object from the first successful driver.
     *
     * @throws LocationNotFoundException If all drivers in the chain fail.
     */
    public function locate(string $ip): Location
    {
        $drivers = $this->config['drivers'] ?? [];
        $errors = [];

        foreach ($drivers as $driverName) {
            try {
                // We resolve each sub-driver from the manager to ensure
                // they are created correctly (e.g., with caching).
                $driver = $this->manager->driver($driverName);

                return $driver->locate($ip);
            } catch (Throwable $e) {
                $errors[] = "Driver '{$driverName}' failed: {$e->getMessage()}";
            }
        }

        // If the loop completes without returning, all drivers have failed.
        throw new LocationNotFoundException(
            "All drivers in the chain failed to locate the IP. Errors: ".implode('; ', $errors)
        );
    }
}
