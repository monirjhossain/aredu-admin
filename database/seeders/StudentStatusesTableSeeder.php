<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $student_statuses = [
            ['name' => 'File Open'],
            ['name' => 'Payment Not Complete'],
            ['name' => 'Payment Complete'],
            ['name' => 'Document Not Ready'],
            ['name' => 'Document Ready for Submit'],
            ['name' => 'Admission Processing'],
            ['name' => 'Admission Complete'],
            ['name' => 'VISA Processing'],
            ['name' => 'VISA Complete'],
            ['name' => 'VISA Delivered'],
            ['name' => 'Service Complete'],
            ['name' => 'File Back'],
        ];
        DB::table('student_statuses')->insert($student_statuses);
    }
}
