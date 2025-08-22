<?php

namespace MyDaniel\GeoLocation\Commands;

use Illuminate\Console\Command;
use MyDaniel\GeoLocation\Exceptions\InvalidIpAddressException;
use MyDaniel\GeoLocation\Exceptions\LocationNotFoundException;
use MyDaniel\GeoLocation\Facades\GeoLocation;

class LocateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geolocation:locate {ip : The IP address to locate.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display the location information for a given IP address.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $ip = $this->argument('ip');

        try {
            $this->info("Locating IP address: {$ip}...");

            $location = GeoLocation::locate($ip);

            $headers = ['Key', 'Value'];

            $data = collect($location->toArray())->map(function ($value, $key) {
                return ['Key' => $key, 'Value' => $value ?? 'N/A'];
            })->all();

            $this->table($headers, $data);

        } catch (InvalidIpAddressException $e) {
            $this->error("Error {$e->getMessage()}");

            return self::FAILURE;
        } catch (LocationNotFoundException $e) {
            $this->error("Error: {$e->getMessage()}");

            return self::FAILURE;
        } catch (\Exception $e) {
            $this->error("An unexpected error occurred: {$e->getMessage()}");

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
