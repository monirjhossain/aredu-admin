<?php

namespace App\Services;

use App\Models\Account\FinanceEntry;
use App\Models\Account\Invoice;
use App\Models\Account\Wallet;
use App\Models\Currency\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InvoiceCreationService
{
    public function create(Request $request, User $currentUser): Invoice
    {
        return DB::transaction(function () use ($request, $currentUser) {
            $invoice = new Invoice();
            $invoice->fill($request->only($invoice->getFillable()))->save();
            $invoice->user_id = $currentUser->id;

            $currency = Currency::with('latestRate')->findOrFail($request->currency);
            if (!$currency->latestRate) {
                throw new RuntimeException('No exchange rate found for selected currency.');
            }

            $invoice->currency = $currency->code;
            $invoice->exchange_rate = $currency->latestRate->rate;
            $invoice->base_amount = ((float) $request->paid) * $currency->latestRate->rate;

            [$productsPayload, $services] = $this->buildProductsPayload($request);

            if (!$request->filled('student_id')) {
                $productsPayload['student'] = [
                    'full_name' => trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? '')),
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile_no' => $request->mobile_no,
                    'address' => $request->address,
                ];
            }

            $invoice->products = $productsPayload;
            $invoice->documents = $request->documents;
            $invoice->invoice_no = date('Ym') . $invoice->id;
            $invoice->save();

            $financeEntry = FinanceEntry::create([
                'type' => 'income',
                'amount' => $invoice->paid,
                'transaction_date' => now(),
                'student_id' => $invoice->student_id ?? null,
                'created_by' => $currentUser->id,
                'description' => 'Invoice #' . $invoice->invoice_no . ' | ' . $services,
                'reference' => 'INV-' . $invoice->invoice_no,
                'categories' => json_encode($productsPayload['id'] ?? []),
                'category_id' => $productsPayload['id'][0] ?? null,
                'currency' => $invoice->currency,
                'exchange_rate' => $invoice->exchange_rate,
                'base_amount' => $invoice->base_amount,
            ]);

            $this->postWalletTransaction($request, $currentUser, $financeEntry->amount, $financeEntry->id, $financeEntry->description);

            return $invoice;
        });
    }

    private function buildProductsPayload(Request $request): array
    {
        $payload = [
            'product' => [],
            'price' => [],
            'id' => [],
        ];
        $services = '';

        $products = (array) $request->product;
        $prices = (array) $request->price;
        $ids = (array) $request->ids;

        $count = 1;
        foreach ($products as $index => $product) {
            $price = (float) ($prices[$index] ?? 0);
            if ($price == 0.0) {
                continue;
            }

            $payload['product'][] = $product;
            $payload['price'][] = $prices[$index];
            $payload['id'][] = $ids[$index] ?? null;
            $services .= $count . '. ' . $product . ' ';
            $count++;
        }

        return [$payload, trim($services)];
    }

    private function postWalletTransaction(Request $request, User $currentUser, float $amount, int $reference, string $description): void
    {
        if (!$request->filled('wallet_id')) {
            return;
        }

        $wallet = Wallet::where('id', $request->wallet_id)
            ->where('branch_id', $currentUser->branch)
            ->first();

        if (!$wallet) {
            throw new RuntimeException('Selected wallet is invalid for your branch.');
        }

        $wallet->debitCredit('income', $amount, $reference, $description);
    }
}
