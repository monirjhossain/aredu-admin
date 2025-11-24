<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversityStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            ['name' => 'File Processing'],
            ['name' => 'File Submitted'],
            ['name' => 'Application Fee Pending'],
            ['name' => 'Application Fee Paid'],
            ['name' => 'Pass Initial Review'],
            ['name' => 'Interview Pending'],
            ['name' => 'Interview Done'],
            ['name' => 'Pre Admitted'],
            ['name' => 'Admission Confirmation Fee Pending'],
            ['name' => 'Admission Confirmation Fee Paid'],
            ['name' => 'Admitted'],
            ['name' => 'JW Issued'],
            ['name' => 'File Processing Completed'],
            ['name' => 'Reject'],
        ];
        DB::table('university_statuses')->insert($products);
    }
}
