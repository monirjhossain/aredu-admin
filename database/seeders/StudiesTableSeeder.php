<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class StudiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studies = [
            ['name' => 'Diploma'],
            ['name' => 'Bachelor'],
            ['name' => 'MBBS'],
            ['name' => 'Masters'],
            ['name' => 'PhD'],
            ['name' => 'Foundation'],
            ['name' => 'Language Course'],
        ];
        DB::table('studies')->insert($studies);
    }
}
