<?php

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class WebsiteSettingsSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            [
                'q' => 'Is studying in China affordable for Bangladeshi students?',
                'a' => 'Yes. Tuition at public universities and the cost of living are far lower than in the UK, USA or Australia. Many programmes cost a fraction of Western study, and our cost calculator shows the full amount in Taka — including flights from Dhaka.',
            ],
            [
                'q' => 'Do I need IELTS to study in China?',
                'a' => 'Not always. Many English-taught programmes accept a range of English proof, and some do not require IELTS. Chinese-taught programmes usually need an HSK level. Our scholarship checker and counsellors confirm what each university requires.',
            ],
            [
                'q' => 'Can I get a full scholarship?',
                'a' => 'Full scholarships such as the CSC (Chinese Government Scholarship), provincial and university scholarships can cover tuition, accommodation, insurance and a monthly stipend. Use our scholarship checker to see which ones you are likely to qualify for.',
            ],
            [
                'q' => 'Is it safe, and is halal food available?',
                'a' => 'Chinese university cities are generally very safe, with halal canteens, mosques and active Bangladeshi student communities. Each city page covers safety, halal food and community details.',
            ],
            [
                'q' => 'How do you convert my SSC, HSC or CGPA?',
                'a' => 'Our free GPA converter turns your SSC/HSC GPA (out of 5) or university CGPA (out of 4) into the China 100-point score, 4.0 GPA and grade — with an honest read on your admission and scholarship chances.',
            ],
            [
                'q' => 'How long does the admission process take?',
                'a' => 'Most admissions take a few weeks to a couple of months depending on the university and intake. We prepare your file, submit to your chosen universities, and keep you updated at every step.',
            ],
            [
                'q' => 'What documents do I need for a Chinese student visa (X1/X2)?',
                'a' => 'Typically passport, admission letter, JW202/JW201, photos, medical form and completed visa application. We provide a checklist tailored to your case.',
            ],
        ];

        $pairs = [
            'brand' => 'AR Education',
            'phone' => '+8801756502004',
            'whatsapp' => 'https://wa.me/8801756502004',
            'email' => 'info@areducation.net',
            'address' => '10th floor, Tropical Tower, Bata Signal, Elephant Road, Dhaka, Bangladesh',
            'facebook' => 'https://www.facebook.com/AREducationbd',
            'youtube' => 'https://www.youtube.com/@areducationbd',
            'faqs' => $faqs,
        ];

        foreach ($pairs as $key => $value) {
            if (WebsiteSetting::query()->where('key', $key)->exists()) {
                continue;
            }
            WebsiteSetting::setValue($key, $value);
        }
    }
}
