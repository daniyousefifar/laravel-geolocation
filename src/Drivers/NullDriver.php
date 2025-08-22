<?php

namespace MyDaniel\GeoLocation\Drivers;

use MyDaniel\GeoLocation\Location;

/**
 * A "null" driver that returns predefined, static data.
 * Useful for testing and local development.
 */
class NullDriver extends AbstractDriver
{
    /**
     * "Locate" the specified IP address by returning a fixed set of data.
     *
     * This method constructs a Location object using the predefined values
     * from the configuration file, but always injects the actual IP address
     * that was passed to the method.
     *
     * @param  string  $ip The IP address to "locate".
     *
     * @return Location A location object with static data.
     */
    public function locate(string $ip): Location
    {
        return (new Location())
            ->setIp($ip) // Always use the provided IP
            ->setIsoCode($this->config['iso_code'] ?? null)
            ->setCountry($this->config['country'] ?? null)
            ->setCity($this->config['city'] ?? null)
            ->setState($this->config['state'] ?? null)
            ->setPostalCode($this->config['postal_code'] ?? null)
            ->setLat($this->config['lat'] ?? null)
            ->setLon($this->config['lon'] ?? null)
            ->setTimezone($this->config['timezone'] ?? null)
            ->setContinent($this->config['continent'] ?? null)
            ->setCurrency($this->config['currency'] ?? null)
            ->setAsnNumber($this->config['asn_number'] ?? null)
            ->setAsnOrganization($this->config['asn_organization'] ?? null);
    }
}
