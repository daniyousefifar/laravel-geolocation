<?php

namespace MyDaniel\GeoLocation\Drivers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use MyDaniel\GeoLocation\Exceptions\LocationNotFoundException;
use MyDaniel\GeoLocation\Location;

/**
 * Driver for the ip-api.com service.
 *
 * This driver handles communication with the ip-api.com service, which provides
 * detailed geolocation data.
 */
class IPAPIDriver extends AbstractDriver
{
    /**
     * Locate the specified IP address using the ip-api.com API.
     *
     * @param  string  $ip  The IP address to locate.
     *
     * @return Location The location data object.
     *
     * @throws LocationNotFoundException If the API call fails or returns an error.
     */
    public function locate(string $ip): Location
    {
        $isPro = ! empty($this->config['key']);

        $baseUrl = $isPro
            ? 'https://pro.ip-api.com/'
            : 'http://ip-api.com/';

        $fields = 'status,message,country,countryCode,continentCode,regionName,city,zip,lat,lon,timezone,currency,isp,org,as,query';

        $queryParams = [
            'fields' => $fields,
            'lang'   => 'en',
        ];

        if ($isPro) {
            $queryParams['key'] = $this->config['key'];
        }

        $url = "http://ip-api.com/json/{$ip}?fields={$fields}";

        try {
            $response = Http::baseUrl($baseUrl)->get("json/{$ip}", $queryParams);

            $response->throw();

            $data = $response->json();

            if (isset($data['status']) && $data['status'] === 'fail') {
                throw new LocationNotFoundException("ip-api.com API error: {$data['message']}");
            }

            return (new Location())
                ->setIp($data['query'])
                ->setIsoCode($data['countryCode'] ?? null)
                ->setCountry($data['country'] ?? null)
                ->setCity($data['city'] ?? null)
                ->setState($data['regionName'] ?? null)
                ->setPostalCode($data['zip'] ?? null)
                ->setLat($data['lat'] ?? null)
                ->setLon($data['lon'] ?? null)
                ->setTimezone($data['timezone'] ?? null)
                ->setContinent($data['continentCode'] ?? null)
                ->setCurrency(null)
                ->setAsnNumber($data['as'] ?? null)
                ->setAsnOrganization($data['isp'] ?? $data['org'] ?? null);

        } catch (RequestException $e) {
            throw new LocationNotFoundException(
                "Request to ip-api.com failed: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }
}
