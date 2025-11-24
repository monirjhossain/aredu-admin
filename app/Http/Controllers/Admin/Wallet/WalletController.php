<?php

namespace App\Http\Controllers\Admin\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Account\Wallet;
use App\Models\Currency\Currency;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::with('transactions', 'currency')->paginate(10);
        return view('admin.wallets.index', compact('wallets'));
    }

    public function create()
    {
        $currencies = Currency::all();
        return view('admin.wallets.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|integer',
            'name' => 'nullable|string|max:255',
            'currency_id' => 'required|exists:currencies,id',
            'balance' => 'required|numeric',
        ]);

        Wallet::create($data);
        return redirect()->route('wallets.index')->with('success', 'Wallet created successfully.');
    }

    public function edit(Wallet $wallet)
    {
        $currencies = Currency::all();
        return view('admin.wallets.edit', compact('wallet', 'currencies'));
    }

    public function update(Request $request, Wallet $wallet)
    {
        // return $request;
        $data = $request->validate([
            // 'currency_id' => 'required|exists:currencies,id',
            'name' => 'nullable|string|max:255',
            // 'balance' => 'required|numeric',
        ]);

        $wallet->update($data);
        return redirect()->route('wallets.index')->with('success', 'Wallet updated successfully.');
    }

    public function destroy(Wallet $wallet)
    {
        $wallet->transactions()->delete(); // optional
        $wallet->delete();
        return redirect()->route('wallets.index')->with('success', 'Wallet deleted successfully.');
    }
}
