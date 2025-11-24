<?php

namespace App\Http\Controllers\Admin\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Account\Wallet;
use Illuminate\Http\Request;

class WalletTransactionController extends Controller
{
    public function index(Wallet $wallet)
    {
        // Load transactions (latest first)
        $transactions = $wallet->transactions()->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.wallets.transactions', compact('wallet', 'transactions'));
    }
}
