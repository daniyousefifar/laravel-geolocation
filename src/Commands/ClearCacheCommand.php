<?php

namespace MyDaniel\GeoLocation\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Repository as Cache;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geolocation:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the geolocation cache.';

    /**
     * Execute the console command.
     *
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     *
     * @return int
     */
    public function handle(Cache $cache): int
    {
        $this->info('Clearing geolocation cache...');

        $cacheTag = config('geolocation.cache.tag', 'geolocation');

        // Flush cache by tag
        $cache->tags($cacheTag)->flush();

        $this->info('Geolocation cache cleared!');

        return self::SUCCESS;
    }
}
