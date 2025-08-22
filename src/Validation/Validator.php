<?php

namespace MyDaniel\GeoLocation\Validation;

use MyDaniel\GeoLocation\Contracts\ValidatorInterface;
use MyDaniel\GeoLocation\Exceptions\InvalidIpAddressException;

/**
 * Handles the validation of IP addresses.
 *
 * This class implements the ValidatorInterface and contains the logic
 * to check if an IP is valid, public, and not reserved.
 */
class Validator implements ValidatorInterface
{
    /**
     *
     *
     * @param  string  $ip
     *
     * @return void
     *
     * @throws InvalidIpAddressException
     */
    public function validate(string $ip): void
    {
        // Check for overall validity (supports both IPv4 and IPv6)
        if ( ! filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new InvalidIpAddressException("The IP address '{$ip}' is invalid.");
        }

        // The FILTER_FLAG_NO_PRIV_RANGE and FILTER_FLAG_NO_RES_RANGE flags
        // are only applicable to IPv4 addresses.
        $isIPv4 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);

        if ($isIPv4 && ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            throw new InvalidIpAddressException("Private or reserved IPv4 addresses like '{$ip}' cannot be located.");
        }
    }
}
