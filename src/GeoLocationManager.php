<?php

namespace MyDaniel\GeoLocation;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Manager;
use MaxMind\Db\Reader\InvalidDatabaseException;
use MyDaniel\GeoLocation\Contracts\GeoLocationDriver;
use MyDaniel\GeoLocation\Contracts\ValidatorInterface;
use MyDaniel\GeoLocation\Drivers\ChainDriver;
use MyDaniel\GeoLocation\Drivers\IPAPICoDriver;
use MyDaniel\GeoLocation\Drivers\IPAPIDriver;
use MyDaniel\GeoLocation\Drivers\IPLocate;
use MyDaniel\GeoLocation\Drivers\IPQueryDriver;
use MyDaniel\GeoLocation\Drivers\MaxMindDriver;
use MyDaniel\GeoLocation\Drivers\NullDriver;
use MyDaniel\GeoLocation\Exceptions\GeoLocationException;
use MyDaniel\GeoLocation\Exceptions\InvalidIpAddressException;
use Throwable;

/**
 * Class GeoLocationManager
 *
 * Manages the creation of various geolocation drivers and applies caching.
 *
 * @mixin GeolocationDriver
 */
class GeoLocationManager extends Manager
{
    /**
     * The IP address validator instance.
     *
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * GeoLocationManager constructor.
     *
     * @param  Container  $container
     * @param  ValidatorInterface  $validator
     */
    public function __construct(Container $container, ValidatorInterface $validator)
    {
        parent::__construct($container);

        $this->validator = $validator;
    }

    /**
     * The main entry point for locating an IP address.
     *
     * This method handles IP validation, caching, and fallback logic.
     *
     * @param  string  $ip  The IP address to locate.
     *
     * @return Location
     *
     * @throws BindingResolutionException
     * @throws Exceptions\LocationNotFoundException
     * @throws GeoLocationException
     * @throws InvalidIpAddressException
     * @throws Throwable
     */
    public function locate(string $ip): Location
    {
        $this->validator->validate($ip);

        try {
            return $this->driver()->locate($ip);
        } catch (Throwable $e) {
            // If the default driver fails, try the fallback.
            $fallbackDriverName = $this->config->get('geolocation.fallback_driver');

            if ($fallbackDriverName) {
                try {
                    return $this->driver($fallbackDriverName)->locate($ip);
                } catch (Throwable $fallbackException) {
                    // If fallback also fails, throw the original exception.
                    throw new GeoLocationException(
                        "Both primary and fallback drivers failed. Original error: {$e->getMessage()}",
                        0,
                        $e
                    );
                }
            }

            // If no fallback, re-throw the original exception.
            throw $e;
        }
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('geolocation.driver', 'maxmind');
    }

    /**
     * Get a driver instance.
     *
     * Overrides the parent method to wrap the resolved driver
     *  with the cache decorator if caching is enabled.
     *
     * @param  string|null  $driver
     *
     * @return GeoLocationDriver
     *
     * @throws BindingResolutionException
     */

    public function driver($driver = null): GeoLocationDriver
    {
        $driverInstance = parent::driver($driver);

        $cacheConfig = $this->config->get('geolocation.cache');

        if ($cacheConfig['enabled'] === false) {
            return $driverInstance;
        }

        $cacheStore = $this->container->make('cache')->store(
            $cacheConfig['store'] ?? null
        );

        return new CacheDecorator(
            $driverInstance,
            $cacheStore,
            $cacheConfig
        );
    }

    /**
     * Create an instance of the IPLocate driver.
     *
     * @return IPLocate
     */
    public function createIPLocateDriver(): IPLocate
    {
        $config = $this->config->get('geolocation.drivers.iplocate');

        return new IPLocate($config);
    }

    /**
     * Create an instance of the IPQuery driver.
     *
     * @return IPQueryDriver
     */
    public function createIpqueryDriver(): IPQueryDriver
    {
        $config = $this->config->get('geolocation.drivers.ipquery');

        return new IPQueryDriver($config);
    }

    /**
     * Create an instance of the ip-api.com driver.
     *
     * @return IPAPIDriver
     */
    public function createIPAPIDriver(): IPAPIDriver
    {
        $config = $this->config->get('geolocation.drivers.ipapi');

        return new IPAPIDriver($config);
    }

    /**
     * Create an instance of the ipapi.co driver.
     *
     * @return IPAPICoDriver
     */
    public function createIPAPICoDriver(): IPAPICoDriver
    {
        $config = $this->config->get('geolocation.drivers.ipapico');

        return new IPAPICoDriver($config);
    }

    /**
     * Create an instance of the MaxMind DB driver.
     *
     * @return MaxMindDriver
     *
     * @throws InvalidDatabaseException
     */
    public function createMaxmindDriver(): MaxMindDriver
    {
        $config = $this->config->get('geolocation.drivers.maxmind');

        return new MaxMindDriver($config);
    }

    /**
     * Create an instance of the Null driver.
     *
     * @return NullDriver
     */
    public function createNullDriver(): NullDriver
    {
        $config = $this->config->get('geolocation.drivers.null');

        return new NullDriver($config);
    }

    /**
     * Create an instance of the Chain driver.
     *
     * @return ChainDriver
     */
    public function createChainDriver(): ChainDriver
    {
        $config = $this->config->get('geolocation.drivers.chain');

        return new ChainDriver($config, $this);
    }
}
