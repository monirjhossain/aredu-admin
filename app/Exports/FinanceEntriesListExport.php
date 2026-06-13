<?php

namespace App\Exports;

use App\Exports\Concerns\StylesWorksheet;
use App\Models\Account\FinanceEntry;
use App\Models\Student\Student;
use App\Support\StudentDisplay;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinanceEntriesListExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    use StylesWorksheet;

    /** @var Collection<int, FinanceEntry> */
    private Collection $entries;

    protected string $sheetTitle = 'Finance Entries';

    /**
     * @param Collection<int, FinanceEntry> $entries
     */
    public function __construct(Collection $entries)
    {
        $this->entries = $entries;
    }

    public function collection(): Collection
    {
        return $this->entries;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Type',
            'Student ID',
            'Student Name',
            'Category',
            'Description',
            'Reference',
            'Amount',
            'Currency',
            'Created By',
        ];
    }

    /**
     * @param FinanceEntry $entry
     * @return array<int, mixed>
     */
    public function map($entry): array
    {
        return [
            $entry->id,
            $entry->transaction_date,
            $entry->type,
            $entry->student instanceof Student
                ? StudentDisplay::officeStudentId($entry->student)
                : '',
            $entry->student->full_name ?? '',
            $entry->expense->name ?? $entry->income->name ?? '',
            $entry->description,
            $entry->reference,
            $entry->amount,
            $entry->currency,
            $entry->creator->name ?? '',
        ];
    }
}
