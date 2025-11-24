<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            ['name' => 'Admission Processing'],
            ['name' => 'VISA Processing'],
            ['name' => 'Processing Cost'],
            ['name' => 'Service Charge'],
            ['name' => 'Application Fee'],
            ['name' => 'File Opening Charge'],
            ['name' => 'File Closing Charge'],
            ['name' => 'Foreign Student Application Fee'],
            ['name' => 'Foreign Student Service Fee'],
            
            ['name' => 'Air Ticket'],
            ['name' => 'Police Clearance Application'],
            ['name' => 'Affidavit Charge'],
            ['name' => 'University Commission'],

            ['name' => 'Money Exchange'],
            ['name' => 'Investment'],
            // ['name' => 'Money Exchange (RMB to BDT)'],
            // ['name' => 'Money Exchange (USD to BDT)'],
            // ['name' => 'Money Exchange (BDT to USD)'],
            // ['name' => 'Money Exchange (BDT to RMB)'],
            ['name' => 'Others'],
        ];
        DB::table('products')->insert($products);
    }
}
