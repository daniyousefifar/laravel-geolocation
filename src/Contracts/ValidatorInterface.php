<?php

namespace MyDaniel\GeoLocation\Contracts;

use MyDaniel\GeoLocation\Exceptions\InvalidIpAddressException;

/**
 * Defines the contract for an IP address validator.
 */
interface ValidatorInterface
{
    /**
     * Validates an IP address.
     *
     * @param  string  $ip The IP address to validate.
     *
     * @return void
     *
     * @throws InvalidIpAddressException If the IP address is invalid or falls within a private/reserved range.
     */
    public function validate(string $ip): void;
}
