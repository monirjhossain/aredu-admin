<?php

namespace App\Imports;

use App\Models\Account\FinanceEntry;
use App\Models\Account\Invoice;
use App\Models\Condominium\Condominium;
use App\Models\Condominium\CondominiumSubscriptionHistory;
use App\Models\Condominium\ImportTempCondominium;
use App\Models\CountryCode;
use App\Models\Student\Student;
use App\Models\User\ImportedFile;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// class StudentImportWithPayment implements ToCollection, WithStartRow, WithValidation
class StudentImportWithPayment_old implements ToCollection, WithStartRow
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
            $amount = trim($row[5]);
            $university = trim($row[9]);
            $subject = trim($row[10]);
            $payment = trim($row[11]);

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


            // 'study_level',
            // 'subject',
            // 'preferred_country',
            // 'preferred_subject',
            // 'last_education',
            // 'first_name',
            // 'last_name',
            // 'passport',
            // 'email',
            // 'mobile_no',
            // 'address',
            // 'city',
            // 'country',
            // 'zip',
            // 'father_name',
            // 'father_occoupation',
            // 'father_mobile_no',
            // 'mother_name',
            // 'mother_occoupation',
            // 'mother_mobile_no',
            // 'responsible_person',
            // 'office_status',
            // 'note',
            // 'status',
            // 'university_id',
            // 'ssc_result',
            // 'hsc_result',
            // 'intake'


            ]);

            $invoiceCreate = Invoice::create([
                'invoice_no' => 'inv- ' . $id,
                'student_id' => $StudentCreate->id,
                'transaction_date' => date('Y-m-d'),
                'due_date' => null,
                'products' => [],
                'documents' => [],
                'subtotal' => $payment,
                'discount' => 0,
                'tax' => 0,
                'grandtotal' => $payment,
                'paid' => $payment,
                'due' => 0,
                'user_id' => Auth::id(),

            ]);

            $transictionCreate = FinanceEntry::create([

                'type' => 'income',
                'amount' => $payment,
                'transaction_date' => date('Y-m-d'),
                'student_id' => $StudentCreate->id,
                'created_by' => Auth::id(),
                'description' => 'INV- ' . $invoiceCreate->id,
                'reference' => 'INV- ' . $invoiceCreate->id,
                'categories' => [],
                'category' => 4

            ]);
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
        return 4;
    }
}