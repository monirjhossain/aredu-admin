<?php

namespace App\Support;

use App\Models\WebsiteSetting;

class HomeSections
{
    const SETTINGS_KEY = 'home_sections';

    /**
     * Default home page copy (matches current frontend).
     *
     * @return array
     */
    public static function defaults()
    {
        return [
            'visibility' => [
                'hero' => true,
                'stats' => true,
                'popular_courses' => true,
                'popular_universities' => true,
                'tools' => true,
                'tools_banner' => true,
                'ask_experts' => true,
                'services' => true,
                'degrees' => true,
                'fields' => true,
                'scholarships' => true,
                'mid_cta' => true,
                'why' => true,
                'how' => true,
                'gallery' => true,
                'videos' => true,
                'faq' => true,
                'final_cta' => true,
            ],
            'hero' => [
                'headline' => "Study in China,\nguided from Bangladesh.",
                'subheadline' => 'Universities, scholarships and real costs in Taka — with step-by-step help from admission to visa.',
                'primary_cta_label' => 'Find my university',
                'primary_cta_url' => '/tools/find-university',
                'secondary_cta_label' => 'Browse scholarships',
                'secondary_cta_url' => '/scholarships',
                'search_placeholder' => 'Search universities, majors…',
                'slides' => [
                    '/images/hero-bg-1.jpg',
                    '/images/hero-bg-2.jpg',
                    '/images/hero-bg-3.jpg',
                    '/images/hero-bg-4.jpg',
                    '/images/hero-bg-5.jpg',
                    '/images/hero-bg-6.jpg',
                ],
            ],
            'stats' => [
                'eyebrow' => 'AR Education',
                'intro' => 'Numbers that matter for your China journey.',
                'items' => [
                    ['value' => 98, 'label' => 'Universities'],
                    ['value' => 9, 'label' => 'Scholarships'],
                    ['value' => 52, 'label' => 'Cities'],
                    ['value' => 121, 'label' => 'Majors'],
                    ['value' => 2000, 'label' => 'Students'],
                ],
            ],
            'why' => [
                'eyebrow' => 'Why China',
                'title' => 'Why Bangladeshi students choose China.',
                'subtitle' => 'Affordable education, scholarship access and a faster path to globally relevant degrees make China one of the strongest options for Bangladeshi students.',
                'summary_label' => 'Fast summary',
                'summary_title' => 'Lower cost, better options, clearer support.',
                'summary_text' => 'Students can compare tuition in Taka, aim for funded routes and get end-to-end help from Bangladesh.',
                'items' => [
                    [
                        'num' => '01',
                        'title' => 'Affordable in Taka',
                        'desc' => 'See the full cost in BDT before you apply — tuition, living, visa and flights.',
                        'icon' => 'ti-wallet',
                    ],
                    [
                        'num' => '02',
                        'title' => 'Scholarships that pay you',
                        'desc' => 'CSC and provincial awards can cover tuition, dorm, insurance and a monthly stipend.',
                        'icon' => 'ti-rosette-discount-check',
                    ],
                    [
                        'num' => '03',
                        'title' => 'World-ranked degrees',
                        'desc' => '985 & 211 universities with strong engineering, medical and business programmes.',
                        'icon' => 'ti-building-bank',
                    ],
                    [
                        'num' => '04',
                        'title' => 'Guided end-to-end',
                        'desc' => 'From shortlist to student visa — free counselling on WhatsApp, in Bangla.',
                        'icon' => 'ti-users-group',
                    ],
                ],
            ],
            'how' => [
                'eyebrow' => 'How it works',
                'title' => 'Your path from first search to class day.',
                'subtitle' => 'A simple four-step process for Bangladeshi students planning admission, scholarship and visa support for China.',
                'items' => [
                    [
                        'step' => '01',
                        'title' => 'Discover',
                        'desc' => 'Convert grades, estimate costs in Taka, and shortlist universities.',
                        'icon' => 'ti-search',
                    ],
                    [
                        'step' => '02',
                        'title' => 'Shortlist',
                        'desc' => 'Check scholarship eligibility and compare best-fit options.',
                        'icon' => 'ti-list-check',
                    ],
                    [
                        'step' => '03',
                        'title' => 'Apply',
                        'desc' => 'We prepare your file and submit to your chosen universities.',
                        'icon' => 'ti-file-check',
                    ],
                    [
                        'step' => '04',
                        'title' => 'Fly',
                        'desc' => 'Admission, JW202, student visa — then depart Dhaka ready.',
                        'icon' => 'ti-plane-departure',
                    ],
                ],
            ],
            'tools_banner' => [
                'enabled' => true,
                'eyebrow' => 'Study in China',
                'title' => 'Free counselling for your China admission',
                'subtitle' => 'Shortlist universities, check scholarships and start your file with AR Education — guided in Bangla.',
                'cta_label' => 'Talk to a counsellor',
                'cta_url' => '/contact',
                'image' => '/images/hero-bg-3.jpg',
                'mobile_image' => '',
            ],
            'mid_cta' => [
                'title' => 'Not sure where to start?',
                'subtitle' => "Answer a few questions — we'll match universities and scholarships free, in minutes.",
                'primary_cta_label' => 'Find my university →',
                'primary_cta_url' => '/tools/find-university',
                'secondary_cta_label' => 'WhatsApp us',
            ],
            'final_cta' => [
                'title' => 'Ready to study in China?',
                'subtitle' => 'Free counselling in Bangla — pick a university, apply, win a scholarship, reach class day one.',
                'primary_cta_label' => 'Chat on WhatsApp',
                'secondary_cta_label' => 'Find my university',
                'secondary_cta_url' => '/tools/find-university',
            ],
            'faq' => [
                'eyebrow' => 'FAQ',
                'title' => 'Frequently asked questions',
                'subtitle' => 'Quick answers to the questions Bangladeshi students ask most about universities, scholarships, costs and admission in China.',
                'help_label' => 'Need a direct answer?',
                'help_text' => 'If your question is not listed here, our team can guide you directly in Bangla.',
                'help_cta_label' => 'Ask on WhatsApp',
            ],
            'ask_experts' => [
                'eyebrow' => 'Live question desk',
                'title' => 'Ask our experts like a real conversation.',
                'subtitle' => 'Scholarship, budget, documents or visa confusion? Start with one message and our team will guide your next step.',
                'quick_questions' => [
                    'Can I get a full CSC scholarship?',
                    'Do I need IELTS for China?',
                    'What is the total cost in Taka?',
                    'Which university is best for MBBS?',
                    'How long does admission take?',
                    'Is halal food available on campus?',
                ],
                'experts' => [
                    ['initials' => 'AR', 'name' => 'Admission Desk', 'role' => 'University matching', 'color' => '#E11D2E'],
                    ['initials' => 'SC', 'name' => 'Scholarship Coach', 'role' => 'CSC & funding', 'color' => '#0F766E'],
                    ['initials' => 'VS', 'name' => 'Visa Support', 'role' => 'X1 / X2 guidance', 'color' => '#0A1628'],
                ],
            ],
            'tools' => HomeCatalogs::tools(),
            'degrees' => HomeCatalogs::degrees(),
            'field_groups' => HomeCatalogs::fieldGroups(),
            'services_catalog' => HomeCatalogs::services(),
        ];
    }

    /**
     * Public payload for /api/home.
     *
     * @return array
     */
    public static function publicPayload()
    {
        $stored = WebsiteSetting::getValue(self::SETTINGS_KEY, null);
        $decoded = null;
        if (is_string($stored) && $stored !== '') {
            $decoded = json_decode($stored, true);
        } elseif (is_array($stored)) {
            $decoded = $stored;
        }

        return self::mergeDefaults(is_array($decoded) ? $decoded : []);
    }

    /**
     * Persist sections JSON.
     *
     * @param  array  $sections
     * @return void
     */
    public static function save(array $sections)
    {
        WebsiteSetting::setValue(self::SETTINGS_KEY, self::mergeDefaults($sections));
    }

    /**
     * @param  array  $input
     * @return array
     */
    public static function mergeDefaults(array $input)
    {
        $defaults = self::defaults();
        $out = $defaults;

        $listKeys = ['tools', 'degrees', 'field_groups', 'services_catalog'];

        foreach (array_keys($defaults) as $section) {
            if (in_array($section, $listKeys, true)) {
                continue;
            }
            if (!isset($input[$section]) || !is_array($input[$section])) {
                continue;
            }

            if ($section === 'visibility') {
                foreach (array_keys($defaults['visibility']) as $key) {
                    if (array_key_exists($key, $input['visibility'])) {
                        $out['visibility'][$key] = filter_var($input['visibility'][$key], FILTER_VALIDATE_BOOLEAN);
                    }
                }
                // Keep tools_banner.enabled in sync with visibility for older clients.
                if (array_key_exists('tools_banner', $out['visibility'])) {
                    $out['tools_banner']['enabled'] = (bool) $out['visibility']['tools_banner'];
                }
                continue;
            }

            $out[$section] = array_replace_recursive($defaults[$section], $input[$section]);

            if (array_key_exists('enabled', $defaults[$section]) && array_key_exists('enabled', $input[$section])) {
                $out[$section]['enabled'] = filter_var($input[$section]['enabled'], FILTER_VALIDATE_BOOLEAN);
                if ($section === 'tools_banner') {
                    $out['visibility']['tools_banner'] = (bool) $out[$section]['enabled'];
                }
            }

            // Keep list shapes clean when admin posts empty trailing rows.
            if (isset($defaults[$section]['items']) && isset($input[$section]['items']) && is_array($input[$section]['items'])) {
                $out[$section]['items'] = array_values(array_filter($input[$section]['items'], function ($row) {
                    if (!is_array($row)) {
                        return false;
                    }
                    $title = trim((string) ($row['title'] ?? $row['label'] ?? ''));
                    $desc = trim((string) ($row['desc'] ?? ''));
                    $value = $row['value'] ?? null;
                    return $title !== '' || $desc !== '' || $value !== null && $value !== '';
                }));
                if (!count($out[$section]['items'])) {
                    $out[$section]['items'] = $defaults[$section]['items'];
                }
            }

            if (isset($defaults[$section]['slides']) && isset($input[$section]['slides']) && is_array($input[$section]['slides'])) {
                $slides = array_values(array_filter(array_map(function ($slide) {
                    return trim((string) $slide);
                }, $input[$section]['slides'])));
                $out[$section]['slides'] = count($slides) ? $slides : $defaults[$section]['slides'];
            }

            if (isset($defaults[$section]['quick_questions']) && isset($input[$section]['quick_questions']) && is_array($input[$section]['quick_questions'])) {
                $qs = array_values(array_filter(array_map(function ($q) {
                    return trim((string) $q);
                }, $input[$section]['quick_questions'])));
                $out[$section]['quick_questions'] = count($qs) ? $qs : $defaults[$section]['quick_questions'];
            }

            if (isset($defaults[$section]['experts']) && isset($input[$section]['experts']) && is_array($input[$section]['experts'])) {
                $experts = array_values(array_filter($input[$section]['experts'], function ($row) {
                    return is_array($row) && trim((string) ($row['name'] ?? '')) !== '';
                }));
                $out[$section]['experts'] = count($experts) ? $experts : $defaults[$section]['experts'];
            }
        }

        // Flat catalog arrays (not keyed objects).
        foreach (['tools', 'degrees', 'services_catalog'] as $listKey) {
            if (!isset($input[$listKey]) || !is_array($input[$listKey])) {
                continue;
            }
            $rows = array_values(array_filter($input[$listKey], function ($row) use ($listKey) {
                if (!is_array($row)) {
                    return false;
                }
                if ($listKey === 'tools') {
                    return trim((string) ($row['title'] ?? '')) !== '';
                }
                if ($listKey === 'degrees') {
                    return trim((string) ($row['slug'] ?? '')) !== '' || trim((string) ($row['name'] ?? '')) !== '';
                }
                return trim((string) ($row['slug'] ?? '')) !== '' || trim((string) ($row['name'] ?? '')) !== '';
            }));
            $out[$listKey] = count($rows) ? $rows : $defaults[$listKey];
        }

        if (isset($input['field_groups']) && is_array($input['field_groups'])) {
            $groups = array_values(array_filter(array_map(function ($row) {
                return trim((string) $row);
            }, $input['field_groups'])));
            $out['field_groups'] = count($groups) ? $groups : $defaults['field_groups'];
        }

        return $out;
    }
}
