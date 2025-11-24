<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        $currencies = [
            ['code' => 'BDT', 'name' => 'Bangladeshi Taka', 'symbol' => '৳', 'is_base' => true],
            ['code' => 'RMB', 'name' => 'Chinese Yuan', 'symbol' => '¥', 'is_base' => false],
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'is_base' => false],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'is_base' => false],
            ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹', 'is_base' => false],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'is_base' => false],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'is_base' => false],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$', 'is_base' => false],
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$', 'is_base' => false],
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => '﷼', 'is_base' => false],
            ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ', 'is_base' => false],
        ];

        DB::table('currencies')->insert(array_map(function ($currency) {
            return array_merge($currency, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, $currencies));

        // Assign exchange rates against BDT (sample values)
        $exchangeRates = [
            ['currency_id' => 1, 'rate' => 1],      // BDT base
            ['currency_id' => 2, 'rate' => 17.5],     // RMB → BDT
            ['currency_id' => 3, 'rate' => 110],    // USD → BDT
            ['currency_id' => 4, 'rate' => 135],    // GBP → BDT
            ['currency_id' => 5, 'rate' => 1.3],    // INR → BDT
            ['currency_id' => 6, 'rate' => 118],    // EUR → BDT
            ['currency_id' => 7, 'rate' => 0.75],   // JPY → BDT
            ['currency_id' => 8, 'rate' => 70],     // AUD → BDT
            ['currency_id' => 9, 'rate' => 82],     // CAD → BDT
            ['currency_id' => 10, 'rate' => 29],    // SAR → BDT
            ['currency_id' => 11, 'rate' => 30],    // AED → BDT
        ];

        DB::table('exchange_rates')->insert(array_map(function ($rate) {
            return array_merge($rate, [
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, $exchangeRates));
    }
}

