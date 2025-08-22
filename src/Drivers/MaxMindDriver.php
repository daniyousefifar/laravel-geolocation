<?php

namespace MyDaniel\GeoLocation\Drivers;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use InvalidArgumentException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use MyDaniel\GeoLocation\Exceptions\LocationNotFoundException;
use MyDaniel\GeoLocation\Location;

/**
 * Driver for local MaxMind GeoIP2/GeoLite2 databases.
 */
class MaxMindDriver extends AbstractDriver
{
    /**
     * The MaxMind database reader instance.
     *
     * @var Reader
     */
    protected Reader $reader;

    /**
     * MaxMindDriver constructor.
     *
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidDatabaseException If the database file is invalid.
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $databasePath = $this->config['database_path'];

        if (!file_exists($databasePath) || !is_readable($databasePath)) {
            throw new InvalidArgumentException(
                "The MaxMind database file does not exist or is not readable at path: {$databasePath}"
            );
        }

        $this->reader = new Reader($databasePath);
    }

    /**
     * Locate the specified IP address.
     *
     * @param  string  $ip
     *
     * @return Location
     * @throws InvalidDatabaseException
     * @throws LocationNotFoundException
     */
    public function locate(string $ip): Location
    {
        try {
            $record = $this->reader->city($ip);

            return (new Location())
                ->setIp($ip)
                ->setIsoCode($record->country->isoCode)
                ->setCountry($record->country->name)
                ->setCity($record->city->name)
                ->setState($record->mostSpecificSubdivision->isoCode)
                ->setPostalCode($record->postal->code)
                ->setLat($record->location->latitude)
                ->setLon($record->location->longitude)
                ->setTimezone($record->location->timeZone)
                ->setContinent($record->continent->code)
                ->setAsnNumber($record->traits->autonomousSystemNumber)
                ->setAsnOrganization($record->traits->autonomousSystemOrganization);

        } catch (AddressNotFoundException $e) {
            throw new LocationNotFoundException("The address {$ip} is not in the database.", 0, $e);
        }
    }
}
