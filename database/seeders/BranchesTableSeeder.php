<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branch = [
            ['name' => 'Dhaka'],
            ['name' => 'Chittagong'],
            ['name' => 'Rajshahi'],
            ['name' => 'International'],
        ];
        DB::table('branches')->insert($branch);
    }
}
