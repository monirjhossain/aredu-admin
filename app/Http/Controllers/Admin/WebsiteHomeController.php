<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\HomeSections;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebsiteHomeController extends Controller
{
    public function edit()
    {
        $sections = HomeSections::publicPayload();

        return view('admin.website_home.edit', [
            'title' => 'Home Page Content',
            'sections' => $sections,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'tools_banner_image_file' => 'nullable|image|max:4096',
            'tools_banner_mobile_image_file' => 'nullable|image|max:4096',
        ]);
        $heroSlides = preg_split('/\r\n|\r|\n/', (string) $request->input('hero_slides', ''));
        $quickQuestions = preg_split('/\r\n|\r|\n/', (string) $request->input('ask_quick_questions', ''));

        $statsItems = [];
        $statValues = $request->input('stat_value', []);
        $statLabels = $request->input('stat_label', []);
        if (is_array($statValues)) {
            foreach ($statValues as $i => $value) {
                $label = trim((string) ($statLabels[$i] ?? ''));
                $value = trim((string) $value);
                if ($value === '' && $label === '') {
                    continue;
                }
                $statsItems[] = [
                    'value' => is_numeric($value) ? (0 + $value) : $value,
                    'label' => $label,
                ];
            }
        }

        $whyItems = [];
        $whyTitles = $request->input('why_title', []);
        $whyDescs = $request->input('why_desc', []);
        $whyIcons = $request->input('why_icon', []);
        if (is_array($whyTitles)) {
            foreach ($whyTitles as $i => $title) {
                $title = trim((string) $title);
                $desc = trim((string) ($whyDescs[$i] ?? ''));
                if ($title === '' && $desc === '') {
                    continue;
                }
                $whyItems[] = [
                    'num' => str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
                    'title' => $title,
                    'desc' => $desc,
                    'icon' => trim((string) ($whyIcons[$i] ?? 'ti-star')) ?: 'ti-star',
                ];
            }
        }

        $howItems = [];
        $howTitles = $request->input('how_title', []);
        $howDescs = $request->input('how_desc', []);
        $howIcons = $request->input('how_icon', []);
        if (is_array($howTitles)) {
            foreach ($howTitles as $i => $title) {
                $title = trim((string) $title);
                $desc = trim((string) ($howDescs[$i] ?? ''));
                if ($title === '' && $desc === '') {
                    continue;
                }
                $howItems[] = [
                    'step' => str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT),
                    'title' => $title,
                    'desc' => $desc,
                    'icon' => trim((string) ($howIcons[$i] ?? 'ti-circle-check')) ?: 'ti-circle-check',
                ];
            }
        }

        $experts = [];
        $expertNames = $request->input('expert_name', []);
        $expertRoles = $request->input('expert_role', []);
        $expertInitials = $request->input('expert_initials', []);
        $expertColors = $request->input('expert_color', []);
        if (is_array($expertNames)) {
            foreach ($expertNames as $i => $name) {
                $name = trim((string) $name);
                if ($name === '') {
                    continue;
                }
                $experts[] = [
                    'name' => $name,
                    'role' => trim((string) ($expertRoles[$i] ?? '')),
                    'initials' => trim((string) ($expertInitials[$i] ?? 'AR')) ?: 'AR',
                    'color' => trim((string) ($expertColors[$i] ?? '#E11D2E')) ?: '#E11D2E',
                ];
            }
        }

        $visibilityKeys = [
            'hero',
            'stats',
            'popular_courses',
            'popular_universities',
            'tools',
            'tools_banner',
            'ask_experts',
            'services',
            'degrees',
            'fields',
            'scholarships',
            'mid_cta',
            'why',
            'how',
            'gallery',
            'videos',
            'faq',
            'final_cta',
        ];
        $visibility = [];
        foreach ($visibilityKeys as $key) {
            $visibility[$key] = $request->boolean('visibility_' . $key);
        }

        $sections = [
            'visibility' => $visibility,
            'hero' => [
                'headline' => (string) $request->input('hero_headline', ''),
                'subheadline' => (string) $request->input('hero_subheadline', ''),
                'primary_cta_label' => (string) $request->input('hero_primary_cta_label', ''),
                'primary_cta_url' => (string) $request->input('hero_primary_cta_url', ''),
                'secondary_cta_label' => (string) $request->input('hero_secondary_cta_label', ''),
                'secondary_cta_url' => (string) $request->input('hero_secondary_cta_url', ''),
                'search_placeholder' => (string) $request->input('hero_search_placeholder', ''),
                'slides' => $heroSlides,
            ],
            'stats' => [
                'eyebrow' => (string) $request->input('stats_eyebrow', ''),
                'intro' => (string) $request->input('stats_intro', ''),
                'items' => $statsItems,
            ],
            'why' => [
                'eyebrow' => (string) $request->input('why_eyebrow', ''),
                'title' => (string) $request->input('why_section_title', ''),
                'subtitle' => (string) $request->input('why_subtitle', ''),
                'summary_label' => (string) $request->input('why_summary_label', ''),
                'summary_title' => (string) $request->input('why_summary_title', ''),
                'summary_text' => (string) $request->input('why_summary_text', ''),
                'items' => $whyItems,
            ],
            'how' => [
                'eyebrow' => (string) $request->input('how_eyebrow', ''),
                'title' => (string) $request->input('how_section_title', ''),
                'subtitle' => (string) $request->input('how_subtitle', ''),
                'items' => $howItems,
            ],
            'tools_banner' => [
                'enabled' => (bool) $visibility['tools_banner'],
                'eyebrow' => (string) $request->input('tools_banner_eyebrow', ''),
                'title' => (string) $request->input('tools_banner_title', ''),
                'subtitle' => (string) $request->input('tools_banner_subtitle', ''),
                'cta_label' => (string) $request->input('tools_banner_cta_label', ''),
                'cta_url' => (string) $request->input('tools_banner_cta_url', ''),
                'image' => $this->storeBannerImage(
                    $request,
                    'tools_banner_image_file',
                    (string) $request->input('tools_banner_image', '')
                ),
                'mobile_image' => $this->storeBannerImage(
                    $request,
                    'tools_banner_mobile_image_file',
                    (string) $request->input('tools_banner_mobile_image', '')
                ),
            ],
            'mid_cta' => [
                'title' => (string) $request->input('mid_cta_title', ''),
                'subtitle' => (string) $request->input('mid_cta_subtitle', ''),
                'primary_cta_label' => (string) $request->input('mid_cta_primary_label', ''),
                'primary_cta_url' => (string) $request->input('mid_cta_primary_url', ''),
                'secondary_cta_label' => (string) $request->input('mid_cta_secondary_label', ''),
            ],
            'final_cta' => [
                'title' => (string) $request->input('final_cta_title', ''),
                'subtitle' => (string) $request->input('final_cta_subtitle', ''),
                'primary_cta_label' => (string) $request->input('final_cta_primary_label', ''),
                'secondary_cta_label' => (string) $request->input('final_cta_secondary_label', ''),
                'secondary_cta_url' => (string) $request->input('final_cta_secondary_url', ''),
            ],
            'faq' => [
                'eyebrow' => (string) $request->input('faq_eyebrow', ''),
                'title' => (string) $request->input('faq_title', ''),
                'subtitle' => (string) $request->input('faq_subtitle', ''),
                'help_label' => (string) $request->input('faq_help_label', ''),
                'help_text' => (string) $request->input('faq_help_text', ''),
                'help_cta_label' => (string) $request->input('faq_help_cta_label', ''),
            ],
            'ask_experts' => [
                'eyebrow' => (string) $request->input('ask_eyebrow', ''),
                'title' => (string) $request->input('ask_title', ''),
                'subtitle' => (string) $request->input('ask_subtitle', ''),
                'quick_questions' => $quickQuestions,
                'experts' => $experts,
            ],
            'tools' => $this->collectTools($request),
            'degrees' => $this->collectDegrees($request),
            'field_groups' => preg_split('/\r\n|\r|\n/', (string) $request->input('field_groups', '')),
            'services_catalog' => $this->collectServicesCatalog($request),
        ];

        HomeSections::save($sections);

        return redirect()->route('home-content.edit')->with('success', 'Home page content updated.');
    }

    private function storeBannerImage(Request $request, $fileKey, $fallbackUrl)
    {
        if (!$request->hasFile($fileKey)) {
            return trim($fallbackUrl);
        }

        $file = $request->file($fileKey);
        $dir = public_path('uploads/home');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $name = 'banner_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);

        return '/uploads/home/' . $name;
    }

    private function collectTools(Request $request)
    {
        $titles = $request->input('tool_title', []);
        $rows = [];
        if (!is_array($titles)) {
            return $rows;
        }
        foreach ($titles as $i => $title) {
            $title = trim((string) $title);
            if ($title === '') {
                continue;
            }
            $rows[] = [
                'title' => $title,
                'desc' => trim((string) ($request->input('tool_desc')[$i] ?? '')),
                'to' => trim((string) ($request->input('tool_to')[$i] ?? '')),
                'icon' => trim((string) ($request->input('tool_icon')[$i] ?? 'ti-tool')) ?: 'ti-tool',
                'color' => trim((string) ($request->input('tool_color')[$i] ?? '#E11D2E')) ?: '#E11D2E',
                'bg' => trim((string) ($request->input('tool_bg')[$i] ?? '#FCE8EA')) ?: '#FCE8EA',
            ];
        }
        return $rows;
    }

    private function collectDegrees(Request $request)
    {
        $names = $request->input('degree_name', []);
        $rows = [];
        if (!is_array($names)) {
            return $rows;
        }
        foreach ($names as $i => $name) {
            $name = trim((string) $name);
            $slug = trim((string) ($request->input('degree_slug')[$i] ?? ''));
            if ($name === '' && $slug === '') {
                continue;
            }
            $rows[] = [
                'name' => $name,
                'slug' => $slug !== '' ? $slug : Str::slug($name),
                'letter' => trim((string) ($request->input('degree_letter')[$i] ?? '')) ?: strtoupper(substr($name, 0, 1)),
                'label' => trim((string) ($request->input('degree_label')[$i] ?? '')) ?: ($name . ' Programmes in China'),
            ];
        }
        return $rows;
    }

    private function collectServicesCatalog(Request $request)
    {
        $names = $request->input('service_name', []);
        $rows = [];
        if (!is_array($names)) {
            return $rows;
        }
        foreach ($names as $i => $name) {
            $name = trim((string) $name);
            $slug = trim((string) ($request->input('service_slug')[$i] ?? ''));
            if ($name === '' && $slug === '') {
                continue;
            }
            $rows[] = [
                'name' => $name,
                'slug' => $slug !== '' ? $slug : Str::slug($name),
                'short' => trim((string) ($request->input('service_short')[$i] ?? '')),
                'icon' => trim((string) ($request->input('service_icon')[$i] ?? 'ti-briefcase')) ?: 'ti-briefcase',
                'color' => trim((string) ($request->input('service_color')[$i] ?? '#E11D2E')) ?: '#E11D2E',
                'bg' => trim((string) ($request->input('service_bg')[$i] ?? '#FCE8EA')) ?: '#FCE8EA',
            ];
        }
        return $rows;
    }
}
