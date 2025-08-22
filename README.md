# Laravel GeoLocation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mydaniel/laravel-geolocation.svg?style=flat-square)](https://packagist.org/packages/mydaniel/laravel-geolocation)
[![Total Downloads](https://img.shields.io/packagist/dt/mydaniel/laravel-geolocation.svg?style=flat-square)](https://packagist.org/packages/mydaniel/laravel-geolocation)
[![License](https://img.shields.io/packagist/l/mydaniel/laravel-geolocation.svg?style=flat-square)](https://github.com/daniyousefifar/laravel-geolocation/blob/main/LICENSE.md)

A powerful, flexible, and multi-driver geolocation package for Laravel. It provides a simple yet robust way to retrieve geographical information for an IP address from various services.

This package is designed to be highly extensible and resilient, with built-in support for caching, fallback drivers, and an elegant, fluent API.

## Features

- **Multi-Driver Support**: Switch between services with a simple config change.
    - **ip-api.com** (`ipapicom`): Supports both free and Pro tiers.
    - **ipapi.co** (`ipapico`): Simple and free to use.
    - **IPQuery.io** (`ipquery`): A fast and reliable service.
    - **iplocate.io** (`iplocate`): Another excellent API-based driver.
    - **MaxMind** (`maxmind`): Use local GeoLite2/GeoIP2 databases for offline lookups.
    - **Chain Driver** (`chain`): Define a sequence of drivers to try until one succeeds.
    - **Null Driver** (`null`): Returns static data, perfect for testing.
- **Robust Caching**: Automatically caches results to reduce API calls and improve performance. Supports cache tags for easy clearing.
- **Fallback & Chain Logic**: Built-in support for a simple fallback driver or a more powerful chain of drivers to ensure high availability.
- **Rich `Location` Object**: Returns a well-structured DTO with fluent getters and useful helper methods like `distanceTo()`.
- **Artisan Commands**: Includes convenient commands to test lookups and clear the cache directly from your terminal.
- **Extensible Architecture**: Easily add your own custom geolocation driver.
- **IPv4 & IPv6 Support**: Validates and locates both IP address formats.

## Installation

You can install the package via Composer:

```bash
composer require mydaniel/laravel-geolocation
```

Next, publish the configuration file using the `vendor:publish` command:

```bash
php artisan vendor:publish --provider="MyDaniel\GeoLocation\GeoLocationServiceProvider" --tag="geolocation-config"
```

If you plan to use the MaxMind driver, you should also publish the database file. The package includes a placeholder GeoLite2 database.

```bash
php artisan vendor:publish --provider="MyDaniel\GeoLocation\GeoLocationServiceProvider" --tag="geolocation-database"
```

## Configuration

After publishing, the configuration file will be located at `config/geolocation.php`. Here you can define your default driver and configure each service.

### Driver Configuration

Set your default driver and API keys in your `.env` file.

```dotenv
# The default driver to use (e.g., ipapico, ipapicom, ipquery, maxmind, chain)
GEOLOCATION_DRIVER=chain

# API keys for the services you use
IPAPI_KEY=your_ipapicom_pro_key
IPLOCATE_KEY=your_iplocate_key

# --- Chain Driver Example ---
# If GEOLOCATION_DRIVER=chain, it will use the drivers defined in the config.
# By default, it tries 'ipapico' then 'maxmind'.

# --- Fallback Driver ---
# Alternatively, you can define a single fallback driver.
GEOLOCATION_FALLBACK_DRIVER=maxmind
```

### Cache Configuration

Caching is enabled by default. You can configure the cache settings in `config/geolocation.php` or via `.env` variables.

```dotenv
GEOLOCATION_CACHE_ENABLED=true
GEOLOCATION_CACHE_TTL=1440 # In minutes (1440 = 24 hours)
```

## Usage

The package provides a simple and expressive API. Use the `GeoLocation` facade to perform lookups.

### Basic Lookup

```php
use MyDaniel\GeoLocation\Facades\GeoLocation;

$location = GeoLocation::locate('8.8.8.8');

echo $location->getCountry(); // "United States"
echo $location->getCity();     // "Mountain View"
echo $location->getLat();      // 37.422
echo $location->getLon();      // -122.084
```

### The `Location` Object

The `locate` method returns a `MyDaniel\GeoLocation\Location` object, which has the following getters:

- `getIp(): string`
- `getIsoCode(): ?string`
- `getCountry(): ?string`
- `getCity(): ?string`
- `getState(): ?string`
- `getPostalCode(): ?string`
- `getLat(): ?float`
- `getLon(): ?float`
- `getTimezone(): ?string`
- `getContinent(): ?string`
- `getCurrencyCode(): ?string`
- `getCurrencyName(): ?string`
- `getCurrencySymbol(): ?string`
- `getAsnNumber(): ?int`
- `getAsnOrganization(): ?string`
- `isFromCache(): bool`
- `toArray(): array`

### Calculating Distance

You can easily calculate the distance between two locations using the `distanceTo` method.

```php
$locationA = GeoLocation::locate('8.8.8.8');    // Google DNS
$locationB = GeoLocation::locate('1.1.1.1');    // Cloudflare DNS

// Calculate distance in kilometers (default)
$distanceInKm = $locationA->distanceTo($locationB);

// Calculate distance in miles
$distanceInMiles = $locationA->distanceTo($locationB, 'mi');
```

### Checking Cache Status

You can check if the result was retrieved from the cache.

```php
$location = GeoLocation::locate('1.1.1.1');

if ($location->isFromCache()) {
    // This response was served from the cache.
}
```

### Using a Specific Driver

You can use a specific driver on the fly, overriding the default driver from your config file.

```php
$location = GeoLocation::driver('maxmind')->locate('1.1.1.1');
```

## Artisan Commands

The package includes two helpful Artisan commands.

### Locate an IP

To quickly test a lookup from your terminal:

```bash
php artisan geolocation:locate 8.8.8.8
```

### Clear the Geolocation Cache

This command will clear only the cache entries created by this package by flushing the `geolocation` tag.

```bash
php artisan geolocation:clear-cache
```

## Extending the Package

You can easily add your own custom driver.

1.  **Create your driver class** and implement the `MyDaniel\GeoLocation\Contracts\GeolocationDriver` interface.
2.  **Register your driver** in the `boot` method of a service provider (e.g., `app/Providers/AppServiceProvider.php`) using the `extend` method.

<!-- end list -->

```php
// in a Service Provider's boot() method
use MyDaniel\GeoLocation\Facades\GeoLocation;
use App\Services\MyCustomGeoDriver; // Your custom driver class

public function boot()
{
    GeoLocation::extend('my_driver', function ($app) {
        return new MyCustomGeoDriver();
    });
}
```

3.  Set your new driver in the `config/geolocation.php` file or your `.env` file: `GEOLOCATION_DRIVER=my_driver`.

## Contributing

Contributions are welcome\! Please feel free to fork the repository and submit a pull request.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
