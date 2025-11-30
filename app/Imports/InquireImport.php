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
use App\Models\Inquiry\Inquiry;
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
class InquireImport implements ToCollection, WithStartRow
{
    public function __construct() {}

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            
            $name   = trim($row[1]);
            $mobile_no1  = trim($row[3]);
            $mobile_no2  = trim($row[4]);

            if($name !=null && $name != ''){

                $parts = explode(' ', $name);
                $firstName = $parts[0];
                $lastName = trim(substr($name, strlen($firstName)));
                
                $StudentCreate = Inquiry::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'mobile_no' => $mobile_no1 !=null && $name != '' ? $mobile_no1 : $mobile_no2,
                    // 'user_id' => Auth::id(),
                    'type' => 'Rajshahi-Toha',
                    'country' => 'Bangladesh',
                    'country_preferred' => 'china',
                    // 'course' => $mobile_no1,,
                    'last_education' => 'hsc',
                    // 'email',
                    // 'oppurtunity',
                ]);
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
        return 1;
    }
}