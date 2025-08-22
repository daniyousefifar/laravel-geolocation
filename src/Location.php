<?php

namespace MyDaniel\GeoLocation;

use Illuminate\Contracts\Support\Arrayable;

/**
 * A Data Transfer Object (DTO) representing geolocation data.
 *
 * This class encapsulates all location-related information for an IP address.
 * It uses a fluent setter interface for instantiation and provides methods
 * for easy serialization. It also tracks whether the data was retrieved
 * from the cache.
 */
class Location implements Arrayable
{
    /**
     * The IP address that was looked up.
     *
     * @var string
     */
    protected string $ip;

    /**
     * The ISO 3166-1 alpha-2 country code (e.g., "US", "IR").
     *
     * @var string|null
     */
    protected ?string $iso_code = null;

    /**
     * The name of the country (e.g., "United States", "Iran").
     *
     * @var string|null
     */
    protected ?string $country = null;

    /**
     * The name of the city (e.g., "Mountain View", "Tehran").
     *
     * @var string|null
     */
    protected ?string $city = null;

    /**
     * The code for the state or province (e.g., "CA", "THR").
     *
     * @var string|null
     */
    protected ?string $state = null;

    /**
     * The postal code (e.g., "94043").
     *
     * @var string|null
     */
    protected ?string $postal_code = null;

    /**
     * The latitude coordinate.
     *
     * @var float|null
     */
    protected ?float $lat = null;

    /**
     * The longitude coordinate.
     *
     * @var float|null
     */
    protected ?float $lon = null;

    /**
     * The timezone identifier (e.g., "America/Los_Angeles", "Asia/Tehran").
     *
     * @var string|null
     */
    protected ?string $timezone = null;

    /**
     * The continent code (e.g., "NA", "EU").
     *
     * @var string|null
     */
    protected ?string $continent = null;

    /**
     * The currency code (e.g., "USD", "EUR").
     *
     * @var string|null
     */
    protected ?string $currency = null;

    /**
     * The Autonomous System Number (ASN).
     *
     * @var string|null
     */
    protected ?string $asn_number = null;

    /**
     * The organization associated with the ASN (e.g., "Google LLC").
     *
     * @var string|null
     */
    protected ?string $asn_organization = null;

    /**
     * Indicates if the location data was retrieved from the cache.
     *
     * @var bool
     */
    protected bool $from_cache = false;

    /**
     * Create a new Location instance.
     *
     * @param  array<string, mixed>  $attributes  The location attributes.
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Get the IP address.
     *
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * Set the IP address.
     *
     * @param  string  $ip
     *
     * @return $this
     */
    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get the ISO 3166-1 alpha-2 country code.
     *
     * @return string|null
     */
    public function getIsoCode(): ?string
    {
        return $this->iso_code;
    }

    /**
     * Set the ISO 3166-1 alpha-2 country code.
     *
     * @param  string|null  $isoCode
     *
     * @return $this
     */
    public function setIsoCode(?string $isoCode): self
    {
        $this->iso_code = $isoCode;

        return $this;
    }

    /**
     * Get the country name.
     *
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Set the country name.
     *
     * @param  string|null  $country
     *
     * @return $this
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get the city name.
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Set the city name.
     *
     * @param  string|null  $city
     *
     * @return $this
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the state or province code.
     *
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * Set the state or province code.
     *
     * @param  string|null  $state
     *
     * @return $this
     */
    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get the postal code.
     *
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    /**
     * Set the postal code.
     *
     * @param  string|null  $postalCode
     *
     * @return $this
     */
    public function setPostalCode(?string $postalCode): self
    {
        $this->postal_code = $postalCode;

        return $this;
    }

    /**
     * Get the latitude coordinate.
     *
     * @return float|null
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * Set the latitude coordinate.
     *
     * @param  float|null  $lat
     *
     * @return $this
     */
    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get the longitude coordinate.
     *
     * @return float|null
     */
    public function getLon(): ?float
    {
        return $this->lon;
    }

    /**
     * Set the longitude coordinate.
     *
     * @param  float|null  $lon
     *
     * @return $this
     */
    public function setLon(?float $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get the timezone identifier.
     *
     * @return string|null
     */
    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * Set the timezone identifier.
     *
     * @param  string|null  $timezone
     *
     * @return $this
     */
    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get the continent code.
     *
     * @return string|null
     */
    public function getContinent(): ?string
    {
        return $this->continent;
    }

    /**
     * Set the continent code.
     *
     * @param  string|null  $continent
     *
     * @return $this
     */
    public function setContinent(?string $continent): self
    {
        $this->continent = $continent;

        return $this;
    }

    /**
     * Get the currency code.
     *
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Set the currency code.
     *
     * @param  string|null  $currency
     *
     * @return $this
     */
    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the Autonomous System Number (ASN).
     *
     * @return string|null
     */
    public function getAsnNumber(): ?string
    {
        return $this->asn_number;
    }

    /**
     * Set the Autonomous System Number (ASN).
     *
     * @param  string|null  $asnNumber
     *
     * @return $this
     */
    public function setAsnNumber(?string $asnNumber): self
    {
        $this->asn_number = $asnNumber;

        return $this;
    }

    /**
     * Get the ASN organization.
     *
     * @return string|null
     */
    public function getAsnOrganization(): ?string
    {
        return $this->asn_organization;
    }

    /**
     * Set the ASN organization.
     *
     * @param  string|null  $asnOrganization
     *
     * @return $this
     */
    public function setAsnOrganization(?string $asnOrganization): self
    {
        $this->asn_organization = $asnOrganization;

        return $this;
    }

    /**
     * Check if the location data was retrieved from the cache.
     *
     * @return bool
     */
    public function isFromCache(): bool
    {
        return $this->from_cache;
    }

    /**
     * Set the cached status of the location data.
     *
     * @param  bool  $status
     *
     * @return $this
     */
    public function setAsFromCache(bool $status): self
    {
        $this->from_cache = $status;

        return $this;
    }

    /**
     * Calculate the distance to another location.
     *
     * This method uses the Haversine formula to calculate the great-circle
     * distance between two points on a sphere given their longitudes and latitudes.
     *
     * @param  Location  $other  The other location object to calculate the distance to.
     * @param  string  $unit  The desired unit for the result ('km' for kilometers, 'mi' for miles).
     *
     * @return float The calculated distance.
     */
    public function distanceTo(Location $other, string $unit = 'km'): float
    {
        if ($this->getLat() === null || $this->getLon() === null || $other->getLat() === null || $other->getLon() === null) {
            return 0.0;
        }

        $earthRadius = ($unit === 'mi') ? 3959 : 6371; // Radius in miles or kilometers

        $latFrom = deg2rad($this->getLat());
        $lonFrom = deg2rad($this->getLon());
        $latTo   = deg2rad($other->getLat());
        $lonTo   = deg2rad($other->getLon());

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                               cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'ip'               => $this->getIp(),
            'iso_code'         => $this->getIsoCode(),
            'country'          => $this->getCountry(),
            'city'             => $this->getCity(),
            'state'            => $this->getState(),
            'postal_code'      => $this->getPostalCode(),
            'lat'              => $this->getLat(),
            'lon'              => $this->getLon(),
            'timezone'         => $this->getTimezone(),
            'continent'        => $this->getContinent(),
            'currency'         => $this->getCurrency(),
            'asn_number'       => $this->getAsnNumber(),
            'asn_organization' => $this->getAsnOrganization(),
            'from_cache'       => $this->isFromCache(),
        ];
    }
}
