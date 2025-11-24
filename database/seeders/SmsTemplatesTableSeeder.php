<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SmsTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sms_templates = [
            [
                'name' => 'Happy New Year 2025',
                'message' => 'Happy New Year 2024* From AR EDUCATION. Want to study in China? For more: https://areducation.net',
            ],
            
        ];
        DB::table('sms_templates')->insert($sms_templates);
    }
}
