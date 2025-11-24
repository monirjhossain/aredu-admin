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
class StudentImportWithPayment implements ToCollection, WithStartRow
{
    public function __construct() {}

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            
            $id   = trim($row[0]);
            $name   = trim($row[1]);
            $email  = trim($row[2]);
            $passport   = trim($row[5]);
            $mobile_no  = trim($row[6]);
            $amount = trim($row[9]);
            $university = trim($row[10]);
            $subject = trim($row[11]);
            // $payment = trim($row[11]);

            if($name !=null && $name != ''){

                $parts = explode(' ', $name);
                $firstName = $parts[0];
                $lastName = trim(substr($name, strlen($firstName)));
                
                $StudentCreate = Student::create([

                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'passport' => $passport,
                    'mobile_no' => $mobile_no,
                    'user_id' => Auth::id(),
                ]);

                if($amount !=null && $amount != ''){
                    $invoiceCreate = Invoice::create([
                        'invoice_no' => 'inv- ' . $id,
                        'student_id' => $StudentCreate->id,
                        'transaction_date' => date('Y-m-d'),
                        'due_date' => null,
                        'products' => [
                            'product' => ['Service Charge'],
                            'price' => [$amount],
                            'id' => [4],
                        ],
                        'documents' => null,
                        'subtotal' => $amount,
                        'discount' => 0,
                        'tax' => 0,
                        'grandtotal' => $amount,
                        'paid' => $amount,
                        'due' => 0,
                        'user_id' => Auth::id(),
                        'currency' => 'BDT',
                        'exchange_rate' => 1,
                        'base_amount' => $amount,
        
                    ]);
    
                    $transictionCreate = FinanceEntry::create([
        
                        'type' => 'income',
                        'amount' => $amount,
                        'transaction_date' => date('Y-m-d'),
                        'student_id' => $StudentCreate->id,
                        'created_by' => Auth::id(),
                        'description' => 'INV- ' . $invoiceCreate->id,
                        'reference' => 'INV- ' . $invoiceCreate->id,
                        'categories' => [4],
                        'category_id' => 4,

                        'currency' => 'BDT',
                        'exchange_rate' => 1,
                        'base_amount' => $amount,
        
                    ]);

                    $wallet = Wallet::where('user_id', null)->where('currency_id', 1)->first();
                    $wallet->debitCredit('income', $amount, $transictionCreate->id, $transictionCreate->description);
                }
    
                if($university !=null && $university != ''){
                    DB::table('post_student')->insert(array (
                        0 => 
                        array (
                            'student_id' => $StudentCreate->id,
                            'post_id' => $university,
                        ),
                    ));
                }
    
                if($subject !=null && $subject != ''){
                    DB::table('student_subject')->insert(array (
                        0 => 
                        array (
                            'subject_id' => $subject,
                            'student_id' => $StudentCreate->id,
                        ),
                    ));
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
        return 3;
    }
}