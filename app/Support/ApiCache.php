<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiCache
{
    const TTL = 120;
    const GEN_KEY = 'api_cache_generation';

    public static function remember($key, $callback, $ttl = null)
    {
        $seconds = $ttl === null ? self::TTL : (int) $ttl;
        $gen = (int) Cache::get(self::GEN_KEY, 1);

        return Cache::remember('api:' . $gen . ':' . $key, $seconds, $callback);
    }

    public static function requestKey(Request $request, $prefix)
    {
        return $prefix . ':' . md5($request->path() . '?' . (string) $request->getQueryString());
    }

    /**
     * Invalidate all versioned API caches + home/about aggregates.
     */
    public static function bump()
    {
        $gen = (int) Cache::get(self::GEN_KEY, 1);
        Cache::forever(self::GEN_KEY, $gen + 1);

        self::forgetPrefix('website');
    }

    public static function forgetPrefix($prefix)
    {
        Cache::forget('website_home_v1');
        Cache::forget('website_home_v2');
        Cache::forget('website_home_v3');
        Cache::forget('website_about_v1');
        Cache::forget('website_about_v2');
    }
}
