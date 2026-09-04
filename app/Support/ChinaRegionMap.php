<?php

namespace App\Support;

class ChinaRegionMap
{
    /**
     * City/province slug or lowercase name → region label used by the website.
     *
     * @var array<string, string>
     */
    private static $map = [
        'beijing' => 'North China',
        'tianjin' => 'North China',
        'hebei' => 'North China',
        'shijiazhuang' => 'North China',
        'taiyuan' => 'North China',
        'shanxi' => 'North China',
        'shanghai' => 'East China',
        'hangzhou' => 'East China',
        'nanjing' => 'East China',
        'suzhou' => 'East China',
        'ningbo' => 'East China',
        'wuxi' => 'East China',
        'zhejiang' => 'East China',
        'jiangsu' => 'East China',
        'anhui' => 'East China',
        'shandong' => 'East China',
        'fujian' => 'East China',
        'jiangxi' => 'East China',
        'guangzhou' => 'South China',
        'shenzhen' => 'South China',
        'guangdong' => 'South China',
        'fuzhou' => 'South China',
        'xiamen' => 'South China',
        'hainan' => 'South China',
        'wuhan' => 'Central China',
        'hubei' => 'Central China',
        'hunan' => 'Central China',
        'henan' => 'Central China',
        'changsha' => 'Central China',
        'zhengzhou' => 'Central China',
        'chengdu' => 'Southwest China',
        'sichuan' => 'Southwest China',
        'chongqing' => 'Southwest China',
        'yunnan' => 'Southwest China',
        'kunming' => 'Southwest China',
        'guizhou' => 'Southwest China',
        'xian' => 'Northwest China',
        'xi-an' => 'Northwest China',
        'shaanxi' => 'Northwest China',
        'gansu' => 'Northwest China',
        'xinjiang' => 'Northwest China',
        'harbin' => 'Northeast China',
        'dalian' => 'Northeast China',
        'dalian-city' => 'Northeast China',
        'changchun' => 'Northeast China',
        'shenyang' => 'Northeast China',
        'liaoning' => 'Northeast China',
        'jilin' => 'Northeast China',
        'heilongjiang' => 'Northeast China',
        'jinzhou' => 'Northeast China',
    ];

    /**
     * Category names that are type buckets, not cities/provinces.
     *
     * @var string[]
     */
    private static $nonLocationSlugs = [
        'root',
        'chinese-universities',
        'medical-university',
        'engineering-university',
        'engineering-uuniversity',
        'language-course',
        'language-courses',
    ];

    /**
     * @param  string|null  $slug
     * @param  string|null  $name
     * @return string
     */
    public static function regionFor($slug = null, $name = null)
    {
        $keys = array_filter([
            strtolower((string) $slug),
            strtolower(str_replace(' ', '-', (string) $name)),
            strtolower((string) $name),
        ]);

        foreach ($keys as $key) {
            $key = preg_replace('/\s+city$/', '', $key);
            if (isset(self::$map[$key])) {
                return self::$map[$key];
            }
        }

        return 'China';
    }

    /**
     * @param  string|null  $slug
     * @param  string|null  $name
     * @return bool
     */
    public static function isLocationCategory($slug = null, $name = null)
    {
        $slug = strtolower((string) $slug);
        $name = strtolower((string) $name);

        if ($slug === '' || in_array($slug, self::$nonLocationSlugs, true)) {
            return false;
        }

        if (strpos($name, 'universit') !== false) {
            return false;
        }

        if ($name === 'root') {
            return false;
        }

        return true;
    }
}
