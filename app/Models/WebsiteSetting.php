<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsiteSetting extends Model
{
    protected $table = 'website_settings';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function getValue($key, $default = null)
    {
        $row = static::query()->where('key', $key)->first();
        if (!$row) {
            return $default;
        }
        return $row->value;
    }

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public static function setValue($key, $value)
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) || is_object($value) ? json_encode($value) : $value]
        );
        Cache::forget('website_settings_public');
        Cache::forget('website_home_v1');
        Cache::forget('website_home_v2');
        Cache::forget('website_home_v3');
        Cache::forget('website_about_v1');
    }

    /**
     * Public website payload.
     *
     * @return array
     */
    public static function publicPayload()
    {
        return Cache::remember('website_settings_public', 60, function () {
            $defaults = [
                'brand' => 'AR Education',
                'phone' => '+8801756502004',
                'whatsapp' => 'https://wa.me/8801756502004',
                'email' => 'info@areducation.net',
                'address' => '10th floor, Tropical Tower, Bata Signal, Elephant Road, Dhaka, Bangladesh',
                'facebook' => 'https://www.facebook.com/AREducationbd',
                'youtube' => 'https://www.youtube.com/@areducationbd',
                'faqs' => [],
            ];

            $rows = static::query()->pluck('value', 'key');
            foreach ($defaults as $key => $default) {
                if (!$rows->has($key)) {
                    continue;
                }
                $val = $rows->get($key);
                if ($key === 'faqs') {
                    $decoded = json_decode($val, true);
                    $defaults[$key] = is_array($decoded) ? $decoded : [];
                } else {
                    $defaults[$key] = $val;
                }
            }

            return $defaults;
        });
    }
}
