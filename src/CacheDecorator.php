<?php

namespace MyDaniel\GeoLocation;

use MyDaniel\GeoLocation\Contracts\GeoLocationDriver;
use Illuminate\Cache\Repository as Cache;
use MyDaniel\GeoLocation\Exceptions\LocationNotFoundException;

/**
 * Class CacheDecorator
 *
 * A decorator that adds a caching layer over any GeolocationDriver instance.
 */
class CacheDecorator implements GeoLocationDriver
{
    /**
     * The underlying geolocation driver instance.
     *
     * @var GeoLocationDriver
     */
    protected GeoLocationDriver $driver;

    /**
     * The cache repository instance.
     *
     * @var Cache
     */
    protected Cache $cache;

    /**
     * The cache configuration array.
     *
     * @var array<string, mixed>
     */
    protected array $config;

    /**
     * CacheDecorator constructor.
     *
     * @param  GeoLocationDriver  $driver  The driver to be decorated.
     * @param  Cache  $cache  The cache repository.
     * @param  array<string, mixed>  $config  The cache configuration.
     */
    public function __construct(GeoLocationDriver $driver, Cache $cache, array $config)
    {
        $this->driver = $driver;

        $this->cache = $cache;

        $this->config = $config;
    }

    /**
     * Locate the specified IP address, using the cache if available.
     *
     * @param  string  $ip
     *
     * @return Location
     *
     * @throws LocationNotFoundException
     */
    public function locate(string $ip): Location
    {
        if ($this->config['enabled'] === false) {
            return $this->driver->locate($ip);
        }

        $cacheKey = $this->config['prefix'].$ip;
        $cacheTag = $this->config['tag'];

        $cache = $this->cache->tags($cacheTag);

        // Attempt to retrieve from cache first.
        $cachedLocation = $cache->get($cacheKey);
        if ($cachedLocation instanceof Location) {
            // Mark as from cache and return
            return $cachedLocation->setAsFromCache(true);
        }

        // If not in cache, get from the driver.
        $location = $this->driver->locate($ip);

        // Store the new location object in the cache.
        $cache->put($cacheKey, $location, $this->config['ttl'] * 60);

        // This is a fresh result, so cached is false (default)
        return $location;
    }
}
