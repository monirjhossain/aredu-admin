<?php

namespace App\Exports;

use App\Exports\Concerns\StylesWorksheet;
use App\Support\StudentDisplay;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentSummaryExport implements FromCollection, WithHeadings, WithEvents
{
    use StylesWorksheet;

    /** @var array<int, array<string, mixed>> */
    private array $rows;

    protected string $sheetTitle = 'Student Summary';

    public function __construct($entries)
    {
        $this->rows = $this->buildRows($entries);
    }

    public function collection(): Collection
    {
        return collect($this->rows)->map(static function (array $row) {
            return array_values($row);
        });
    }

    public function headings(): array
    {
        if ($this->rows === []) {
            return [];
        }

        return array_keys($this->rows[0]);
    }

    /**
     * @param iterable $entries
     * @return array<int, array<string, mixed>>
     */
    private function buildRows($entries): array
    {
        $rows = [];
        $index = 1;

        foreach ($entries as $entry) {
            $student = $entry->student ?? null;
            $balance = ($entry->income_bdt - $entry->expense_bdt) + ($entry->income_rmb - $entry->expense_rmb);

            $rows[] = [
                '#' => $index++,
                'Student ID' => $student ? StudentDisplay::officeStudentId($student) : '',
                'Student Name' => $student->full_name ?? '',
                'Description' => $entry->description ?? '',
                'Reference' => $entry->reference ?? '',
                'Income BDT' => $entry->income_bdt ?? 0,
                'Income RMB' => $entry->income_rmb ?? 0,
                'Expense BDT' => $entry->expense_bdt ?? 0,
                'Expense RMB' => $entry->expense_rmb ?? 0,
                'Total Balance' => $balance,
            ];
        }

        return $rows;
    }
}
