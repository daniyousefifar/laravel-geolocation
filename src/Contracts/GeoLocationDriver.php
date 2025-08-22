<?php

namespace MyDaniel\GeoLocation\Contracts;

use MyDaniel\GeoLocation\Exceptions\LocationNotFoundException;
use MyDaniel\GeoLocation\Location;

/**
 * Interface GeoLocationDriver
 *
 * Defines the contract that all geolocation drivers must implement.
 */
interface GeoLocationDriver
{
    /**
     * Get the location information for the given IP address.
     *
     * @param  string  $ip  The IP address to locate.
     *
     * @return Location The location data object.
     *
     * @throws LocationNotFoundException If the location for the IP cannot be found.
     */
    public function locate(string $ip): Location;
}
