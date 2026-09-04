<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicePostsSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'slug' => 'application-documents-ready',
                'name' => 'Application & Documents Ready',
                'short' => 'We prepare your complete application file and submit it to your chosen universities.',
                'long' => 'From transcripts and recommendation letters to online portals — we assemble a complete, university-ready application package and submit it on time.',
                'icon' => 'ti-file-description',
                'color' => '#E11D2E',
                'bg' => '#FCE8EA',
            ],
            [
                'slug' => 'visa-process-visa-fee',
                'name' => 'Visa Process & Visa Fee',
                'short' => 'Complete X1/X2 student-visa support handled from Dhaka.',
                'long' => 'Once you have admission and JW202/JW201, we guide the full China student visa process from Dhaka — forms, appointments and fee clarity.',
                'icon' => 'ti-plane-departure',
                'color' => '#0F766E',
                'bg' => '#E6F4F2',
            ],
            [
                'slug' => 'medical-in-bangladesh',
                'name' => 'Medical (in Bangladesh)',
                'short' => 'Guidance for the required medical examination, done in Bangladesh.',
                'long' => 'Chinese universities often require a physical examination record. We guide you to complete the medical properly in Bangladesh before departure.',
                'icon' => 'ti-heartbeat',
                'color' => '#B45309',
                'bg' => '#FEF3C7',
            ],
            [
                'slug' => 'pickup-drop-and-china-facilities',
                'name' => 'Pickup & Drop and China Facilities',
                'short' => 'Airport pickup, drop and settling-in support after you land in China.',
                'long' => 'Landing in a new country is easier with support. We arrange airport pickup and help you settle into dorms and campus life.',
                'icon' => 'ti-car',
                'color' => '#0A1628',
                'bg' => '#E8EEF4',
            ],
            [
                'slug' => 'csca-university-interview-duolingo-support',
                'name' => 'CSCA, University Interview & Duolingo Support',
                'short' => 'Preparation for CSCA, university interviews and the Duolingo English Test.',
                'long' => 'Many Chinese universities now ask for CSCA scores, interview rounds and English proof. We prepare you step-by-step — practice sessions, mock interviews and Duolingo strategies — so you walk in confident.',
                'icon' => 'ti-microphone',
                'color' => '#0F766E',
                'bg' => '#E6F4F2',
            ],
        ];

        $authorId = DB::table('users')->orderBy('id')->value('id') ?: 1;
        $now = now();

        foreach ($items as $item) {
            $existing = DB::table('posts')
                ->where('post_type', 'service')
                ->where('post_name', $item['slug'])
                ->first();

            $payload = [
                'post_author' => $authorId,
                'post_title' => $item['name'],
                'post_name' => $item['slug'],
                'post_excerpt' => $item['short'],
                'post_content' => '<p>' . e($item['long']) . '</p>',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_type' => 'service',
                'status' => 1,
                'updated_at' => $now,
                'deleted_at' => null,
            ];

            if ($existing) {
                DB::table('posts')->where('id', $existing->id)->update($payload);
                $postId = $existing->id;
            } else {
                $payload['created_at'] = $now;
                $payload['comment_count'] = 0;
                $postId = DB::table('posts')->insertGetId($payload);
            }

            $this->upsertMeta($postId, 'icon', $item['icon']);
            $this->upsertMeta($postId, 'color', $item['color']);
            $this->upsertMeta($postId, 'bg', $item['bg']);
        }
    }

    private function upsertMeta($postId, $key, $value)
    {
        $existing = DB::table('post_metas')
            ->where('post_id', $postId)
            ->where('meta_key', $key)
            ->first();

        if ($existing) {
            DB::table('post_metas')->where('id', $existing->id)->update([
                'meta_value' => $value,
                'updated_at' => now(),
            ]);
            return;
        }

        DB::table('post_metas')->insert([
            'post_id' => $postId,
            'meta_key' => $key,
            'meta_value' => $value,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
