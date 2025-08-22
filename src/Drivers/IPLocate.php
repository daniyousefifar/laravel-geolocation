<?php

namespace MyDaniel\GeoLocation\Drivers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use MyDaniel\GeoLocation\Exceptions\LocationNotFoundException;
use MyDaniel\GeoLocation\Location;

/**
 * Driver for the iplocate.io service.
 */
class IPLocate extends AbstractDriver
{
    /**
     * Locate the specified IP address.
     *
     * @params string $ip The IP address to locate.
     *
     * @return Location The location data object.
     *
     * @throws LocationNotFoundException|\Illuminate\Http\Client\RequestException If the API call fails or returns an error.
     */
    public function locate(string $ip): Location
    {
        $apiKey  = $this->config['key'] ?? null;
        $baseUrl = "https://iplocate.io/api/lookup/{$ip}";

        $queryParams = [];
        if ( ! empty($apiKey)) {
            $queryParams['apiKey'] = $apiKey;
        }

        $url = $baseUrl . (empty($queryParams) ? '' : '?' . http_build_query($queryParams));

        try {
            $response = Http::get($url);
            $response->throw();

            $data = $response->json();

            if (isset($data['error'])) {
                throw new LocationNotFoundException("iplocate.io API error: {$data['error']}");
            }

            return (new Location())
                ->setIp($data['ip'])
                ->setIsoCode($data['country_code'])
                ->setCountry($data['country'])
                ->setCity($data['city'] ?? null)
                ->setState($data['subdivision'] ?? null)
                ->setPostalCode($data['postal_code'])
                ->setLat($data['latitude'])
                ->setLon($data['longitude'])
                ->setTimezone($data['time_zone'] ?? null)
                ->setContinent($data['continent'])
                ->setCurrency($data['currency_code'])
                ->setAsnNumber($data['asn']['asn'])
                ->setAsnOrganization($data['asn']['name']);

        } catch (RequestException $e) {
            throw new LocationNotFoundException(
                "Request to iplocate.io failed: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }
}
