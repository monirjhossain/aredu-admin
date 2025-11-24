<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account\Wallet;

class WalletSeeder extends Seeder
{
    public function run()
    {
        $wallets = [
            ['user_id' => null, 'name' => 'Main Office Wallet', 'currency_id' => 1, 'balance' => 0], // BDT
            ['user_id' => null, 'name' => 'RMB Wallet', 'currency_id' => 2, 'balance' => 0],        // RMB
            ['user_id' => null, 'name' => 'USD Wallet', 'currency_id' => 3, 'balance' => 0],        // USD
        ];

        foreach ($wallets as $wallet) {
            Wallet::create($wallet);
        }
    }
}
