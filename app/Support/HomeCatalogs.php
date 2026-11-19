<?php

namespace App\Support;

class HomeCatalogs
{
    public static function tools()
    {
        return [
            [
                'to' => '/tools/find-university',
                'title' => 'Find my university',
                'desc' => 'Answer a few questions and get your best-fit matches, ranked.',
                'bg' => '#FCE8EA',
                'color' => '#E11D2E',
                'icon' => 'ti-search',
            ],
            [
                'to' => '/tools/scholarship-checker',
                'title' => 'Scholarship checker',
                'desc' => 'See which CSC, provincial and university scholarships fit you.',
                'bg' => '#FEF3C7',
                'color' => '#B45309',
                'icon' => 'ti-award',
            ],
            [
                'to' => '/tools/cost-calculator',
                'title' => 'Cost calculator',
                'desc' => 'Tuition, living, visa and flights from Dhaka — totalled in Taka.',
                'bg' => '#E8EEF4',
                'color' => '#0A1628',
                'icon' => 'ti-calculator',
            ],
            [
                'to' => '/tools/gpa-converter',
                'title' => 'GPA converter',
                'desc' => 'Turn SSC/HSC or CGPA into the China 100-point score & grade.',
                'bg' => '#E6F4F2',
                'color' => '#0F766E',
                'icon' => 'ti-chart-arrows',
            ],
        ];
    }

    public static function degrees()
    {
        return [
            ['slug' => 'bachelor', 'name' => 'Bachelor', 'letter' => 'B', 'label' => 'Bachelor Programmes in China'],
            ['slug' => 'master', 'name' => 'Master', 'letter' => 'M', 'label' => 'Master Programmes in China'],
            ['slug' => 'phd', 'name' => 'PhD', 'letter' => 'P', 'label' => 'PhD Programmes in China'],
            ['slug' => 'mbbs', 'name' => 'MBBS', 'letter' => 'M', 'label' => 'MBBS Programmes in China'],
            ['slug' => 'diploma', 'name' => 'Diploma', 'letter' => 'D', 'label' => 'Diploma Programmes in China'],
            ['slug' => 'foundation', 'name' => 'Foundation', 'letter' => 'F', 'label' => 'Foundation Programmes in China'],
            ['slug' => 'language', 'name' => 'Language', 'letter' => 'L', 'label' => 'Language Programmes in China'],
        ];
    }

    public static function fieldGroups()
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

    public static function services()
    {
        return [
            [
                'slug' => 'application-documents-ready',
                'name' => 'Application & Documents Ready',
                'short' => 'We prepare your complete application file and submit it to your chosen universities.',
                'icon' => 'ti-file-description',
                'color' => '#E11D2E',
                'bg' => '#FCE8EA',
            ],
            [
                'slug' => 'visa-process-visa-fee',
                'name' => 'Visa Process & Visa Fee',
                'short' => 'Complete X1/X2 student-visa support handled from Dhaka.',
                'icon' => 'ti-plane-departure',
                'color' => '#0F766E',
                'bg' => '#E6F4F2',
            ],
            [
                'slug' => 'medical-in-bangladesh',
                'name' => 'Medical (in Bangladesh)',
                'short' => 'Guidance for the required medical examination, done in Bangladesh.',
                'icon' => 'ti-heartbeat',
                'color' => '#B45309',
                'bg' => '#FEF3C7',
            ],
            [
                'slug' => 'pickup-drop-and-china-facilities',
                'name' => 'Pickup & Drop and China Facilities',
                'short' => 'Airport pickup, drop and settling-in support after you land in China.',
                'icon' => 'ti-car',
                'color' => '#0A1628',
                'bg' => '#E8EEF4',
            ],
            [
                'slug' => 'csca-university-interview-duolingo-support',
                'name' => 'CSCA, University Interview & Duolingo Support',
                'short' => 'Preparation for CSCA, university interviews and the Duolingo English Test.',
                'icon' => 'ti-microphone',
                'color' => '#0F766E',
                'bg' => '#E6F4F2',
            ],
        ];
    }
}
