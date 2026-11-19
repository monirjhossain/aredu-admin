<?php

namespace App\Support;

class SubjectFieldMap
{
    /**
     * Canonical field labels used by website catalogs / filters.
     *
     * @return string[]
     */
    public static function groups()
    {
        return [
            'Agriculture & Life Sciences',
            'Architecture & Urban Planning',
            'Arts, Design & Media',
            'Business & Economics',
            'Chinese Language & Preparatory',
            'Computer Science & IT',
            'Education',
            'Engineering & Technology',
            'Humanities & Languages',
            'Medicine & Health',
            'Natural Sciences',
            'Social Sciences & Law',
        ];
    }

    /**
     * Guess a field group from a subject/major name.
     *
     * @param  string  $name
     * @param  int  $index
     * @return string
     */
    public static function guess($name, $index = 0)
    {
        $lower = strtolower((string) $name);
        $groups = self::groups();

        if (preg_match('/medic|mbbs|pharm|nurs|dental|clinical|health|hospital/', $lower)) {
            return 'Medicine & Health';
        }
        if (preg_match('/engineer|technolog|mechanical|civil|electrical|automat|mechatron/', $lower)) {
            return 'Engineering & Technology';
        }
        if (preg_match('/computer|software|data|artificial|iot|network|informat/', $lower)) {
            return 'Computer Science & IT';
        }
        if (preg_match('/business|econom|trade|financ|account|mba|management|market/', $lower)) {
            return 'Business & Economics';
        }
        if (preg_match('/agricult|biolog|life science|environ|forestry|animal|plant/', $lower)) {
            return 'Agriculture & Life Sciences';
        }
        if (preg_match('/chinese|language|hsk|preparatory|foundation/', $lower)) {
            return 'Chinese Language & Preparatory';
        }
        if (preg_match('/education|teach|pedagog/', $lower)) {
            return 'Education';
        }
        if (preg_match('/law|social|politic|sociolog|international relation/', $lower)) {
            return 'Social Sciences & Law';
        }
        if (preg_match('/art|design|media|music|film|fashion/', $lower)) {
            return 'Arts, Design & Media';
        }
        if (preg_match('/architect|urban|planning|landscape/', $lower)) {
            return 'Architecture & Urban Planning';
        }
        if (preg_match('/physic|chemist|math|statistic|material|natural/', $lower)) {
            return 'Natural Sciences';
        }
        if (preg_match('/humanit|history|philosoph|literatur|linguist|english/', $lower)) {
            return 'Humanities & Languages';
        }

        return $groups[$index % count($groups)];
    }
}
