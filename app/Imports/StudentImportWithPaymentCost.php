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
class StudentImportWithPaymentCost implements ToCollection, WithStartRow
{
    public function __construct() {}

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $id   = trim($row[0]);
            $name   = trim($row[1]);
            $passport  = trim($row[2]);
            $fileOpeningCharge  = trim($row[3]);
            $servieCharge  = trim($row[4]);
            $embassyFee  = trim($row[5]);
            $medicalFee  = trim($row[6]);
            $eduprCost  = trim($row[7]);
            $universityAppFee  = trim($row[8]);
            $ticket  = trim($row[9]);
            $studentReference  = trim($row[10]);
            $mobile_no = trim($row[11]);
            $note = trim($row[12]);

            if($name !=null && $name != ''){
                $checkStudent = $this->checkStudent($passport);
                if($checkStudent){
                    $StudentCreate = $checkStudent;
                    $StudentCreate->ref = $studentReference;
                    $StudentCreate->note = $note;
                    $StudentCreate->save();
                }else{
                    $parts = explode(' ', $name);
                    $firstName = $parts[0];
                    $lastName = trim(substr($name, strlen($firstName)));

                    $StudentCreate = Student::create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        // 'email' => $email,
                        'passport' => $passport,
                        'mobile_no' => $mobile_no,
                        'ref' => $studentReference,
                        'note' => $note,
                        'user_id' => Auth::id(),
                    ]);
                }

                if($fileOpeningCharge !=null && $fileOpeningCharge != ''){
                    $this->InvoiceCreateByIncome($fileOpeningCharge, $StudentCreate, 'BDT', 'File Opening Charge', 6);
                }
                if($servieCharge !=null && $servieCharge != ''){
                    $this->InvoiceCreateByIncome($servieCharge, $StudentCreate, 'BDT', 'Servie Charge', 4);
                }

                if($embassyFee !=null && $embassyFee != ''){
                    $this->FinanceCreateByCost($embassyFee, $StudentCreate, 'BDT', 'Embassy Fee', 13);
                }
                if($medicalFee !=null && $medicalFee != ''){
                    $this->FinanceCreateByCost($medicalFee, $StudentCreate, 'BDT', 'Medical Fee', 15);
                }

                if($eduprCost !=null && $eduprCost != ''){
                    $parts = explode('+', $eduprCost);
                    $result = [];
                    foreach ($parts as $part) {
                        $pair = explode('-', $part, 2); // limit 2, avoids accidental extra splits
                        if (count($pair) == 2) {
                            $key = trim($pair[0]);
                            $value = (int) trim($pair[1]);
                            // $result[$key] = $value;
                            $this->FinanceCreateByCost($value, $StudentCreate, 'RMB', 'Edupr Cost - ' . $key, 19);
                        }
                    }
                }
            
                if($universityAppFee !=null && $universityAppFee != ''){
                    $parts = explode('+', $universityAppFee);
                    $result = [];
                    foreach ($parts as $part) {
                        $pair = explode('-', $part, 2); // limit 2, avoids accidental extra splits
                        if (count($pair) == 2) {
                            $key = trim($pair[0]);
                            $value = (int) trim($pair[1]);
                            // $result[$key] = $value;
                            $this->FinanceCreateByCost($value, $StudentCreate, 'RMB', 'University App Fee - ' . $key, 10);
                        }
                    }
                }
                if($ticket !=null && $ticket != ''){
                    $this->FinanceCreateByCost($ticket, $StudentCreate, 'BDT', 'Ticket', 17);
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
        if($passport){
            $student = Student::where('passport', $passport)->first();
            if($student){
                return $student;
            }else{
                return false;
            }
        }
        return false;
    }
    public function InvoiceCreateByIncome($amount, $StudentCreate, $currency, $type, $productOrExpenseId)
    {
        $latestCurrency = Currency::where('code', $currency)->with('latestRate')->first();

        $invoiceCreate = Invoice::create([
            'invoice_no' => 'inv- ' . $StudentCreate->id,
            'student_id' => $StudentCreate->id,
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
            'student_id' => $StudentCreate->id,
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
        return 1;
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