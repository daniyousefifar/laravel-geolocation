<?php

namespace MyDaniel\GeoLocation\Drivers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use MyDaniel\GeoLocation\Exceptions\LocationNotFoundException;
use MyDaniel\GeoLocation\Location;

/**
 * Driver for the ipapi.co service.
 *
 * This driver handles communication with the ipapi.co API. It does not
 * require an API key for its free tier.
 */
class IPAPICoDriver extends AbstractDriver
{
    /**
     * Locate the specified IP address using the ipapi.co API.
     *
     * @param  string  $ip  The IP address to locate.
     *
     * @return Location The location data object.
     *
     * @throws LocationNotFoundException
     */
    public function locate(string $ip): Location
    {
        $apiKey  = $this->config['key'] ?? null;
        $baseUrl = "https://ipapi.co/{$ip}/json/";

        $queryParams = [];
        if ( ! empty($apiKey)) {
            $queryParams['key'] = $apiKey;
        }

        $url = $baseUrl.(empty($queryParams) ? '' : '?'.http_build_query($queryParams));

        try {
            $response = Http::get($url);
            $response->throw();

            $data = $response->json();

            if (isset($data['error'])) {
                throw new LocationNotFoundException("ipapi.co API error: {$data['reason']}");
            }

            return (new Location())
                ->setIp($data['ip'])
                ->setIsoCode($data['country_code'] ?? null)
                ->setCountry($data['country_name'] ?? null)
                ->setCity($data['city'] ?? null)
                ->setState($data['region'] ?? null)
                ->setPostalCode($data['postal'] ?? null)
                ->setLat($data['latitude'] ?? null)
                ->setLon($data['longitude'] ?? null)
                ->setTimezone($data['timezone'] ?? null)
                ->setContinent($data['continent_code'] ?? null)
                ->setCurrency($data['currency'] ?? null)
                ->setAsnNumber($data['asn'] ?? null)
                ->setAsnOrganization($data['org'] ?? null);

        } catch (RequestException $e) {
            throw new LocationNotFoundException(
                "Request to ipapi.co failed: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }
}
