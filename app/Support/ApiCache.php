<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiCache
{
    const TTL = 120;

    public static function remember($key, $callback, $ttl = null)
    {
        $seconds = $ttl === null ? self::TTL : (int) $ttl;
        return Cache::remember('api:' . $key, $seconds, $callback);
    }

    public static function requestKey(Request $request, $prefix)
    {
        return $prefix . ':' . md5($request->path() . '?' . (string) $request->getQueryString());
    }

    public static function forgetPrefix($prefix)
    {
        // File/array cache cannot flush by prefix easily; rely on short TTL.
        Cache::forget('website_home_v1');
    }
}
