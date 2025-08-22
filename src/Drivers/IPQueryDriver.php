<?php

namespace MyDaniel\GeoLocation\Drivers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use MyDaniel\GeoLocation\Exceptions\LocationNotFoundException;
use MyDaniel\GeoLocation\Location;

/**
 * Driver for the IPQuery.io service.
 *
 * This driver handles the communication with the IPQuery API to fetch
 * geolocation data for a given IP address.
 */
class IPQueryDriver extends AbstractDriver
{
    /**
     * Locate the specified IP address using the IPQuery.io API.
     *
     * @param  string  $ip  The IP address to locate.
     *
     * @return Location The location data object.
     *
     * @throws LocationNotFoundException If the API call fails or returns an error.
     */
    public function locate(string $ip): Location
    {
        $url = "https://api.ipquery.io/{$ip}";

        try {
            $response = Http::get($url);
            $response->throw(); // Throw exception on 4xx or 5xx HTTP errors

            $data = $response->json();

            return (new Location())
                ->setIp($ip)
                ->setIsoCode($data['location']['country_code'] ?? null)
                ->setCountry($data['location']['country'] ?? null)
                ->setCity($data['location']['city'] ?? null)
                ->setState($data['location']['state'] ?? null)
                ->setPostalCode($data['location']['zipcode'] ?? null)
                ->setLat($data['location']['latitude'] ?? null)
                ->setLon($data['location']['longitude'] ?? null)
                ->setTimezone($data['location']['timezone'] ?? null)
                ->setAsnNumber($data['isp']['asn'] ?? null)
                ->setAsnOrganization($data['isp']['org'] ?? null);

        } catch (RequestException $e) {
            // Handle network or HTTP errors
            $message = $e->response->json('message') ?? $e->getMessage();
            throw new LocationNotFoundException(
                "Request to IPQuery.io failed: {$message}",
                $e->getCode(),
                $e
            );
        }
    }
}
