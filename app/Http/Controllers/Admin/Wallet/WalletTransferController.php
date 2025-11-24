<?php

namespace App\Http\Controllers\Admin\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Account\Wallet;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletTransferController extends Controller
{
    public function create()
    {
        $wallets = Wallet::with('currency')->get();
        return view('admin.wallets.transfer', compact('wallets'));
    }

    public function store(Request $request, WalletService $service)
    {
        // return $request;
        $data = $request->validate([
            'from_wallet_id' => 'required|exists:wallets,id',
            'to_wallet_id'   => 'required|exists:wallets,id|different:from_wallet_id',
            'amount'         => 'required|numeric|min:0.01',
            'reference'      => 'nullable|string|max:255',
        ]);
        
        $fromWallet = Wallet::findOrFail($data['from_wallet_id']);
        $toWallet   = Wallet::findOrFail($data['to_wallet_id']);
        
        $result = $service->transfer($fromWallet, $toWallet, $data['amount'], $data['reference']);

        return redirect()->route('wallets.index')->with(
            'success',
            "Transferred {$result['debit_amount']} {$fromWallet->currency->code} 
             → {$result['credit_amount']} {$toWallet->currency->code}"
        );
    }
}
