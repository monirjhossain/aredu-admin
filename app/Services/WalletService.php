<?php

namespace App\Services;

use App\Models\Account\Wallet;
use App\Models\Currency\Currency;
use App\Models\Currency\ExchangeRate;
use Illuminate\Support\Facades\DB;
use Exception;

class WalletService
{
    /**
     * Transfer between wallets (same or different currencies).
     */
    public function transfer(Wallet $fromWallet, Wallet $toWallet, float $amount, string $reference)
    {
        return DB::transaction(function () use ($fromWallet, $toWallet, $amount, $reference) {
            if ($fromWallet->balance < $amount) {
                throw new Exception('Insufficient balance in source wallet.');
            }
            // dd('arnob');
            $convertedAmount = 0;
            // If same currency: 1:1 transfer
            if ($fromWallet->currency_id === $toWallet->currency_id) {
                $convertedAmount = $amount;
            } else {
                // Get latest exchange rate
                $convertedAmount = $this->convertCurrency(
                    $amount,
                    $fromWallet->currency_id,
                    $toWallet->currency_id
                );
            }

            // dd($convertedAmount);

            // Debit from source wallet
            $fromWallet->debitCredit('expens', $amount, "Transfer to Wallet #{$toWallet->id} Name:{$toWallet->name} Currency:{$toWallet->code} | {$reference}");

            //  public function debitCredit($type, float $amount, $reference = null, $description = null)

            // Credit destination wallet
            $toWallet->debitCredit('income', $convertedAmount, "Transfer from Wallet #{$fromWallet->id} Name:{$fromWallet->name} Currency:{$fromWallet->code} | {$reference}");

            return [
                'from_wallet' => $fromWallet,
                'to_wallet'   => $toWallet,
                'debit_amount' => $amount,
                'credit_amount' => $convertedAmount,
            ];
        });
    }

    /**
     * Convert currency using latest exchange rate.
     */
    public function convertCurrency(float $amount, int $fromCurrencyId, int $toCurrencyId): float
    {
        $fromCurrency = Currency::findOrFail($fromCurrencyId);
        $toCurrency   = Currency::findOrFail($toCurrencyId);

        if ($fromCurrency->id === $toCurrency->id) {
            return $amount; // no conversion needed
        }

        // Base currency assumed to be is_base = true
        // $baseCurrency = Currency::where('is_base', true)->firstOrFail();

        // Convert from "fromCurrency" to base, then to "toCurrency"
        $fromRate = ExchangeRate::where('currency_id', $fromCurrency->id)->latest('date')->first();
        $toRate   = ExchangeRate::where('currency_id', $toCurrency->id)->latest('date')->first();

        if (!$fromRate || !$toRate) {
            throw new Exception("Missing exchange rate data.");
        }


        // Normalize via base currency
        $amountInBase = $amount * $fromRate->rate;
        $converted    = $amountInBase / $toRate->rate;
//         echo 'Amount: '. $amount . ', // Rate:' . $fromRate->rate . ', // base amount:' . $amountInBase . '/ to rate' . $toRate->rate;
//         echo 'BDT Amount: '. $amountInBase . 'tk, // converted USD:' . $converted;
// dd($converted);
        return $converted;
    }
}

// public function transfer(Request $request, WalletService $service)
// {
//     $data = $request->validate([
//         'from_wallet_id' => 'required|exists:wallets,id',
//         'to_wallet_id'   => 'required|exists:wallets,id|different:from_wallet_id',
//         'amount'         => 'required|numeric|min:0.01',
//         'reference'      => 'nullable|string',
//     ]);

//     $fromWallet = Wallet::findOrFail($data['from_wallet_id']);
//     $toWallet   = Wallet::findOrFail($data['to_wallet_id']);

//     $result = $service->transfer($fromWallet, $toWallet, $data['amount'], $data['reference'] ?? null);

//     return redirect()->back()->with('success', "Transferred {$result['debit_amount']} {$fromWallet->currency->code} to {$toWallet->currency->code} ({$result['credit_amount']} credited).");
// }





// namespace App\Services;

// use App\Models\Account\Wallet;
// use Illuminate\Support\Facades\DB;

// class WalletService
// {
//     public function deposit(Wallet $wallet, float $amount)
//     {
//         DB::transaction(function () use ($wallet, $amount) {
//             $wallet->increment('balance', $amount);
//             $wallet->transactions()->create([
//                 'type' => 'deposit',
//                 'amount' => $amount,
//                 'currency' => $wallet->currency,
//             ]);
//         });
//     }

//     public function withdraw(Wallet $wallet, float $amount)
//     {
//         if ($wallet->balance < $amount) {
//             throw new \Exception("Insufficient funds");
//         }

//         DB::transaction(function () use ($wallet, $amount) {
//             $wallet->decrement('balance', $amount);
//             $wallet->transactions()->create([
//                 'type' => 'withdraw',
//                 'amount' => $amount,
//                 'currency' => $wallet->currency,
//             ]);
//         });
//     }

//     public function transfer(Wallet $from, Wallet $to, float $amount)
//     {
//         if ($from->balance < $amount) {
//             throw new \Exception("Insufficient funds");
//         }

//         DB::transaction(function () use ($from, $to, $amount) {
//             $from->decrement('balance', $amount);
//             $to->increment('balance', $amount);

//             $from->transactions()->create([
//                 'type' => 'transfer',
//                 'amount' => $amount,
//                 'currency' => $from->currency,
//                 'related_wallet_id' => $to->id,
//             ]);

//             $to->transactions()->create([
//                 'type' => 'deposit',
//                 'amount' => $amount,
//                 'currency' => $to->currency,
//                 'related_wallet_id' => $from->id,
//             ]);
//         });
//     }
// }
