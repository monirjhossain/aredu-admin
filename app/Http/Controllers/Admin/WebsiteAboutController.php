<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AboutPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebsiteAboutController extends Controller
{
    public function edit()
    {
        $about = AboutPageContent::publicPayload();

        return view('admin.website_about.edit', [
            'title' => 'About Page Content',
            'about' => $about,
            'visibilityLabels' => AboutPageContent::visibilityLabels(),
            'statsRows' => $this->padRows($about['stats'], 6, ['value' => '', 'label' => '']),
            'valueRows' => $this->padRows($about['values']['items'], 6, ['title' => '', 'description' => '', 'icon' => 'ti-check']),
            'journeyRows' => $this->padRows($about['journey']['steps'], 6, ['title' => '', 'description' => '', 'icon' => 'ti-circle-check']),
            'teamRows' => $this->padRows($about['team']['members'], 6, [
                'initials' => '',
                'name' => '',
                'role' => '',
                'bio' => '',
                'color' => '#D61F26',
                'photo' => '',
            ]),
            'trustRows' => $this->padRows($about['trust']['items'], 6, ['title' => '', 'description' => '', 'icon' => 'ti-check']),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_image_1_file' => 'nullable|image|max:4096',
            'hero_image_2_file' => 'nullable|image|max:4096',
            'hero_image_3_file' => 'nullable|image|max:4096',
            'story_image_file' => 'nullable|image|max:4096',
            'team_photo_file.*' => 'nullable|image|max:4096',
        ]);

        $visibility = [];
        foreach (array_keys(AboutPageContent::visibilityLabels()) as $key) {
            $visibility[$key] = $request->boolean('visibility_' . $key);
        }

        $stats = [];
        $statValues = $request->input('stat_value', []);
        $statLabels = $request->input('stat_label', []);
        if (is_array($statValues)) {
            foreach ($statValues as $i => $value) {
                $value = trim((string) $value);
                $label = trim((string) ($statLabels[$i] ?? ''));
                if ($value === '' && $label === '') {
                    continue;
                }
                $stats[] = ['value' => $value, 'label' => $label];
            }
        }

        $valueItems = [];
        $valueTitles = $request->input('value_title', []);
        if (is_array($valueTitles)) {
            foreach ($valueTitles as $i => $title) {
                $title = trim((string) $title);
                if ($title === '') {
                    continue;
                }
                $valueItems[] = [
                    'title' => $title,
                    'description' => trim((string) ($request->input('value_description')[$i] ?? '')),
                    'icon' => trim((string) ($request->input('value_icon')[$i] ?? 'ti-check')) ?: 'ti-check',
                ];
            }
        }

        $journeySteps = [];
        $stepTitles = $request->input('journey_title', []);
        if (is_array($stepTitles)) {
            foreach ($stepTitles as $i => $title) {
                $title = trim((string) $title);
                if ($title === '') {
                    continue;
                }
                $journeySteps[] = [
                    'title' => $title,
                    'description' => trim((string) ($request->input('journey_description')[$i] ?? '')),
                    'icon' => trim((string) ($request->input('journey_icon')[$i] ?? 'ti-circle-check')) ?: 'ti-circle-check',
                ];
            }
        }

        $members = [];
        $names = $request->input('team_name', []);
        $existingPhotos = $request->input('team_photo', []);
        if (is_array($names)) {
            foreach ($names as $i => $name) {
                $name = trim((string) $name);
                if ($name === '') {
                    continue;
                }
                $photo = trim((string) ($existingPhotos[$i] ?? ''));
                if ($request->hasFile("team_photo_file.$i")) {
                    $photo = $this->storeImage($request->file("team_photo_file.$i"), 'team');
                }
                $members[] = [
                    'name' => $name,
                    'role' => trim((string) ($request->input('team_role')[$i] ?? '')),
                    'bio' => trim((string) ($request->input('team_bio')[$i] ?? '')),
                    'initials' => trim((string) ($request->input('team_initials')[$i] ?? '')) ?: strtoupper(substr($name, 0, 2)),
                    'color' => trim((string) ($request->input('team_color')[$i] ?? '#D61F26')) ?: '#D61F26',
                    'photo' => $photo,
                ];
            }
        }

        $trustItems = [];
        $trustTitles = $request->input('trust_title', []);
        if (is_array($trustTitles)) {
            foreach ($trustTitles as $i => $title) {
                $title = trim((string) $title);
                if ($title === '') {
                    continue;
                }
                $trustItems[] = [
                    'title' => $title,
                    'description' => trim((string) ($request->input('trust_description')[$i] ?? '')),
                    'icon' => trim((string) ($request->input('trust_icon')[$i] ?? 'ti-check')) ?: 'ti-check',
                ];
            }
        }

        $payload = [
            'visibility' => $visibility,
            'hero' => [
                'eyebrow' => (string) $request->input('hero_eyebrow', ''),
                'title' => (string) $request->input('hero_title', ''),
                'subtitle' => (string) $request->input('hero_subtitle', ''),
                'primary_cta_label' => (string) $request->input('hero_primary_cta_label', ''),
                'primary_cta_url' => (string) $request->input('hero_primary_cta_url', ''),
                'secondary_cta_label' => (string) $request->input('hero_secondary_cta_label', ''),
                'image_1' => $this->maybeUpload($request, 'hero_image_1_file', (string) $request->input('hero_image_1', ''), 'hero'),
                'image_2' => $this->maybeUpload($request, 'hero_image_2_file', (string) $request->input('hero_image_2', ''), 'hero'),
                'image_3' => $this->maybeUpload($request, 'hero_image_3_file', (string) $request->input('hero_image_3', ''), 'hero'),
            ],
            'stats' => $stats,
            'story' => [
                'eyebrow' => (string) $request->input('story_eyebrow', ''),
                'title' => (string) $request->input('story_title', ''),
                'body' => (string) $request->input('story_body', ''),
                'quote' => (string) $request->input('story_quote', ''),
                'quote_author' => (string) $request->input('story_quote_author', ''),
                'image' => $this->maybeUpload($request, 'story_image_file', (string) $request->input('story_image', ''), 'story'),
            ],
            'values' => [
                'eyebrow' => (string) $request->input('values_eyebrow', ''),
                'title' => (string) $request->input('values_title', ''),
                'subtitle' => (string) $request->input('values_subtitle', ''),
                'items' => $valueItems,
            ],
            'journey' => [
                'eyebrow' => (string) $request->input('journey_eyebrow', ''),
                'title' => (string) $request->input('journey_title_section', ''),
                'subtitle' => (string) $request->input('journey_subtitle', ''),
                'steps' => $journeySteps,
            ],
            'team' => [
                'eyebrow' => (string) $request->input('team_eyebrow', ''),
                'title' => (string) $request->input('team_title', ''),
                'subtitle' => (string) $request->input('team_subtitle', ''),
                'members' => $members,
            ],
            'trust' => [
                'eyebrow' => (string) $request->input('trust_eyebrow', ''),
                'title' => (string) $request->input('trust_section_title', ''),
                'subtitle' => (string) $request->input('trust_subtitle', ''),
                'items' => $trustItems,
            ],
            'cta' => [
                'title' => (string) $request->input('cta_title', ''),
                'subtitle' => (string) $request->input('cta_subtitle', ''),
                'button_label' => (string) $request->input('cta_button_label', ''),
                'button_url' => (string) $request->input('cta_button_url', ''),
                'secondary_label' => (string) $request->input('cta_secondary_label', ''),
            ],
        ];

        AboutPageContent::save($payload);

        return redirect()->route('about-content.edit')->with('success', 'About page content updated.');
    }

    private function padRows(array $rows, $min, array $blank)
    {
        $rows = array_values($rows);
        while (count($rows) < $min) {
            $rows[] = $blank;
        }
        return $rows;
    }

    private function maybeUpload(Request $request, $fileKey, $fallback, $prefix)
    {
        if (!$request->hasFile($fileKey)) {
            return trim($fallback);
        }
        return $this->storeImage($request->file($fileKey), $prefix);
    }

    private function storeImage($file, $prefix)
    {
        $dir = public_path('uploads/about');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $name = $prefix . '_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $name);
        return '/uploads/about/' . $name;
    }
}
