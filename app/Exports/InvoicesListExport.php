<?php

namespace App\Exports;

use App\Exports\Concerns\StylesWorksheet;
use App\Models\Account\Invoice;
use App\Models\Student\Student;
use App\Support\StudentDisplay;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesListExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    use StylesWorksheet;

    /** @var Collection */
    private $invoices;

    protected string $sheetTitle = 'Invoices';

    public function __construct(Collection $invoices)
    {
        $this->invoices = $invoices;
    }

    public function collection()
    {
        return $this->invoices;
    }

    public function headings(): array
    {
        return self::exportHeadings();
    }

    /**
     * @return string[]
     */
    public static function exportHeadings(): array
    {
        return [
            'ID',
            'Invoice No',
            'Student ID',
            'Student Name',
            'Phone',
            'Subtotal',
            'Discount',
            'Total',
            'Paid',
            'Due',
            'Currency',
            'Invoice Date',
            'Added By',
            'Branch ID',
        ];
    }

    /**
     * @param Invoice $invoice
     * @return array<int, mixed>
     */
    public static function mapInvoiceRow(Invoice $invoice): array
    {
        return [
            $invoice->id,
            $invoice->invoice_no,
            $invoice->student instanceof Student
                ? StudentDisplay::officeStudentId($invoice->student)
                : '',
            $invoice->displayStudentFullName(),
            $invoice->displayStudentPhone(),
            $invoice->subtotal,
            $invoice->discount,
            $invoice->grandtotal,
            $invoice->paid,
            $invoice->due,
            $invoice->currency,
            $invoice->invoice_date,
            $invoice->user->name ?? '',
            $invoice->branch_id,
        ];
    }

    /**
     * @param Invoice $invoice
     * @return array<int, mixed>
     */
    public function map($invoice): array
    {
        return self::mapInvoiceRow($invoice);
    }
}
