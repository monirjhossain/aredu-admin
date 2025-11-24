<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpensesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expenses = [
            ['name' => 'Office Rent & Service Charge'],
            ['name' => 'Employee Salary'],
            ['name' => 'Food'],
            ['name' => 'Office Accessories'],
            ['name' => 'Transport bill'],
            ['name' => 'Mobile Bill'],

            ['name' => 'Offline Marketing'],
            ['name' => 'Online Marketing'],

            ['name' => 'University Visit'],
            ['name' => 'University Application Fee'],
            ['name' => 'University Application Fee (Foreign Student)'],

            ['name' => 'Airport Pickup & Drop'],

            ['name' => 'Embassy Fee'],
            ['name' => 'Embassy Visit'],

            ['name' => 'Medical Cost'],
            ['name' => 'Medical Visit'],
            ['name' => 'Air Ticket'],

            ['name' => 'Education Expo'],
            ['name' => 'EDUPR China'],

            ['name' => 'File Back (Refund)'],
            ['name' => 'Police Clearance Application Fee'],
            ['name' => 'Affidavit Charge'],

            ['name' => 'Money Exchange'],
            // ['name' => 'Money Exchange (RMB to BDT)'],
            // ['name' => 'Money Exchange (USD to BDT)'],
            // ['name' => 'Money Exchange (BDT to USD)'],
            // ['name' => 'Money Exchange (BDT to RMB)'],
            ['name' => 'Others'],





        ];
        DB::table('expenses')->insert($expenses);
    }
}
