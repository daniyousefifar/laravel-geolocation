<?php

namespace MyDaniel\GeoLocation\Drivers;

use MyDaniel\GeoLocation\Contracts\GeoLocationDriver;

/**
 * Class AbstractDriver
 *
 * Provides a base implementation for geolocation drivers,
 * handling the storage of configuration.
 */
abstract class AbstractDriver implements GeoLocationDriver
{
    /**
     * The driver's configuration array.
     *
     * @var array<string, mixed>
     */
    protected array $config;

    /**
     * AbstractDriver constructor.
     *
     * @param  array<string, mixed>  $config  The configuration for the driver.
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
