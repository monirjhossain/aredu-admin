<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScholarshipPostsSeeder extends Seeder
{
    /**
     * Seed website scholarship pages as posts (post_type=scholarship).
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'slug' => 'university-scholarship',
                'name' => 'University Scholarship',
                'coverage' => 'Varies',
                'universities' => 25,
                'desc' => 'University-funded awards that vary by campus — often tuition discounts or living support for international students.',
            ],
            [
                'slug' => 'fully-funded-with-stipend',
                'name' => 'Fully Funded With Stipend',
                'coverage' => 'Full',
                'universities' => 20,
                'desc' => 'A fully funded award covering tuition and providing a monthly stipend for living costs.',
            ],
            [
                'slug' => 'provincial-government-scholarship',
                'name' => 'Provincial Government Scholarship',
                'coverage' => 'Varies',
                'universities' => 8,
                'desc' => 'Provincial government scholarships vary by province and may cover tuition, living costs or both.',
            ],
            [
                'slug' => 'chinese-government-scholarship-csc',
                'name' => 'Chinese Government Scholarship CSC',
                'coverage' => 'Full',
                'universities' => 12,
                'desc' => 'The Chinese Government Scholarship (CSC) is a fully funded national award covering tuition, accommodation, insurance and a monthly stipend.',
            ],
            [
                'slug' => 'half-tuition-50-scholarship',
                'name' => 'Half Tuition 50 Scholarship',
                'coverage' => 'Partial',
                'universities' => 19,
                'desc' => 'A partial scholarship that waives 50% of the tuition fee, reducing the overall cost of study.',
            ],
            [
                'slug' => 'full-scholarship-tuition-stipend',
                'name' => 'Full Scholarship Tuition Stipend',
                'coverage' => 'Full',
                'universities' => 12,
                'desc' => 'A full scholarship covering tuition fees plus a monthly living stipend, and usually free or subsidised accommodation.',
            ],
            [
                'slug' => 'tuition-free-scholarship',
                'name' => 'Tuition Free Scholarship',
                'coverage' => 'Full tuition',
                'universities' => 37,
                'desc' => 'A scholarship that waives 100% of tuition fees. Living costs and accommodation are usually separate.',
            ],
            [
                'slug' => 'tuition-subsidy',
                'name' => 'Tuition Subsidy',
                'coverage' => 'Partial',
                'universities' => 12,
                'desc' => 'A partial reduction of tuition fees offered by the university or a local government.',
            ],
            [
                'slug' => 'partial-scholarship',
                'name' => 'Partial Scholarship',
                'coverage' => 'Partial',
                'universities' => 17,
                'desc' => 'A partial award covering part of the tuition or costs. The exact percentage varies by university.',
            ],
        ];

        $authorId = DB::table('users')->orderBy('id')->value('id') ?: 1;
        $now = now();

        foreach ($items as $item) {
            $existing = DB::table('posts')
                ->where('post_type', 'scholarship')
                ->where('post_name', $item['slug'])
                ->first();

            $payload = [
                'post_author' => $authorId,
                'post_title' => $item['name'],
                'post_name' => $item['slug'],
                'post_excerpt' => $item['desc'],
                'post_content' => '<p>' . e($item['desc']) . '</p>',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'ping_status' => 'closed',
                'post_type' => 'scholarship',
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

            $this->upsertMeta($postId, 'coverage', $item['coverage']);
            $this->upsertMeta($postId, 'universities', (string) $item['universities']);
        }
    }

    /**
     * @param  int  $postId
     * @param  string  $key
     * @param  string  $value
     * @return void
     */
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
