<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class WebsiteSettingController extends Controller
{
    public function edit()
    {
        $settings = WebsiteSetting::publicPayload();
        $faqs = $settings['faqs'];
        if (!is_array($faqs) || !count($faqs)) {
            $faqs = [
                ['q' => '', 'a' => ''],
                ['q' => '', 'a' => ''],
            ];
        }

        return view('admin.website_settings.edit', [
            'title' => 'Website Settings',
            'settings' => $settings,
            'faqs' => $faqs,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:120',
            'phone' => 'nullable|string|max:60',
            'whatsapp' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:120',
            'address' => 'nullable|string|max:500',
            'facebook' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
        ]);

        $keys = ['brand', 'phone', 'whatsapp', 'email', 'address', 'facebook', 'youtube'];
        foreach ($keys as $key) {
            WebsiteSetting::setValue($key, $request->input($key, ''));
        }

        $faqs = [];
        $questions = $request->input('faq_q', []);
        $answers = $request->input('faq_a', []);
        if (is_array($questions)) {
            foreach ($questions as $i => $q) {
                $q = trim((string) $q);
                $a = trim((string) (isset($answers[$i]) ? $answers[$i] : ''));
                if ($q === '' && $a === '') {
                    continue;
                }
                $faqs[] = ['q' => $q, 'a' => $a];
            }
        }
        WebsiteSetting::setValue('faqs', $faqs);

        return redirect()->route('setting.website.edit')->with('success', 'Website settings updated.');
    }
}
