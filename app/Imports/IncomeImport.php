<?php

namespace App\Imports;

use App\Models\Account\FinanceEntry;
use App\Models\Condominium\Condominium;
use App\Models\Condominium\CondominiumSubscriptionHistory;
use App\Models\Condominium\ImportTempCondominium;
use App\Models\CountryCode;
use App\Models\Account\Invoice;
use App\Models\Account\Wallet;
use App\Models\Currency\Currency;
use App\Models\Student\Student;
use App\Models\User\ImportedFile;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// class StudentImportWithPayment implements ToCollection, WithStartRow, WithValidation
class IncomeImport implements ToCollection, WithStartRow
{
    public function __construct() {}

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $id   = trim($row[0]);
            $product_id   = trim($row[1]);
            $description  = trim($row[2]);
            $amount  = trim($row[3]);
            // $servieCharge  = trim($row[4]);
            // $embassyFee  = trim($row[5]);
            // $medicalFee  = trim($row[6]);
            // $eduprCost  = trim($row[7]);
            // $universityAppFee  = trim($row[8]);
            // $ticket  = trim($row[9]);
            // $studentReference  = trim($row[10]);
            // $mobile_no = trim($row[11]);
            // $note = trim($row[12]);

            if($description !=null && $description != ''){
                if($amount !=null && $amount != ''){
                    echo $product_id . ', ';
                    $createInvoice = $this->InvoiceCreateByIncome($amount, null, 'BDT', $description, $product_id);
                    echo $createInvoice . ', ';
                }
            }

        }
    }

    // public function rules(): array
    // {
    //     return [
    //         '*.1' => 'name',
    //         '*.2' => 'email',
    //         '*.3' => 'passport',
    //         '*.4' => 'id',
    //         '*.5' => 'dd',
    //         '*.12' => 'ssdfds',
    //     ];
    // }

    /**
     * @return array
     */
    // public function customValidationAttributes()
    // {
    //     return [
    //         '1' => 'Name',
    //         '2' => 'Codice fiscale',
    //         '3' => 'Indirizzo',
    //         '4' => 'CAP',
    //         '5' => 'Città',
    //         '12' => 'IBAN'
    //     ];
    // }

    public function startRow(): int
    {
        return 2;
    }
    public function checkStudent($passport)
    {
        $student = Student::where('passport', $passport)->first();
        if($student){
            return $student;
        }else{
            return false;
        }
    }
    public function InvoiceCreateByIncome($amount, $student_id, $currency, $type, $productOrExpenseId)
    {
        $latestCurrency = Currency::where('code', $currency)->with('latestRate')->first();
        $invoiceCreate = Invoice::create([
            'invoice_no' => 'inv- ' . $student_id,
            'student_id' => $student_id,
            'transaction_date' => date('Y-m-d'),
            'due_date' => null,
            'products' => [
                'product' => [$type],
                'price' => [$amount],
                'id' => [$productOrExpenseId],
            ],
            'documents' => null,
            'subtotal' => $amount,
            'discount' => 0,
            'tax' => 0,
            'grandtotal' => $amount,
            'paid' => $amount,
            'due' => 0,
            'user_id' => Auth::id(),
            'currency' => $currency,
            'exchange_rate' => $latestCurrency->latestRate->rate,
            'base_amount' => $amount * $latestCurrency->latestRate->rate,
        ]);

        $transictionCreate = FinanceEntry::create([

            'type' => 'income',
            'amount' => $amount,
            'transaction_date' => date('Y-m-d'),
            'student_id' => $student_id,
            'created_by' => Auth::id(),
            'description' => $type,
            'reference' => 'INV- ' . $invoiceCreate->id,
            'categories' => [$productOrExpenseId],
            'category_id' => $productOrExpenseId,

            'currency' => $currency,
            'exchange_rate' => $latestCurrency->latestRate->rate,
            'base_amount' => $amount * $latestCurrency->latestRate->rate,

        ]);

        $wallteId = $currency == 'BDT' ? 1 : ($currency == 'RMB' ? 2 : 3);
        $wallet = Wallet::where('user_id', null)->where('id', $wallteId)->first();
        $wallet->debitCredit('income', $amount, $transictionCreate->id, $transictionCreate->description);
        return $invoiceCreate->id;
    }

    public function FinanceCreateByCost($amount, $StudentCreate, $currency, $type, $productOrExpenseId)
    {
        $latestCurrency = Currency::where('code', $currency)->with('latestRate')->first();
        $transictionCreate = FinanceEntry::create([
            'type' => 'expense',
            'amount' => $amount,
            'transaction_date' => date('Y-m-d'),
            'student_id' => $StudentCreate->id,
            'created_by' => Auth::id(),
            'description' => $type,
            'reference' => $type,
            'categories' => [$productOrExpenseId],
            'category_id' => $productOrExpenseId,

            'currency' => $currency,
            'exchange_rate' => $latestCurrency->latestRate->rate,
            'base_amount' => $amount * $latestCurrency->latestRate->rate,

        ]);

        $wallteId = $currency == 'BDT' ? 1 : ($currency == 'RMB' ? 2 : 3);
        $wallet = Wallet::where('user_id', null)->where('id', $wallteId)->first();
        $wallet->debitCredit('expense', $amount, $transictionCreate->id, $transictionCreate->description);
        return 1;
    }
}