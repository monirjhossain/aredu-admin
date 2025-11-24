<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $education_levels = [
            ['name' => 'Secondary School Certificate'],
            ['name' => 'Higher Secondary School Certificate'],
            ['name' => 'O Level'],
            ['name' => 'A Level'],
            ['name' => 'GCSE'],
            ['name' => 'IB'],
            ['name' => 'Diploma'],
            ['name' => 'Bachelor'],
            ['name' => 'Master\'s'],
            ['name' => 'PhD'],
            ['name' => 'International'],
        ];
        DB::table('education_levels')->insert($education_levels);
    }
}
