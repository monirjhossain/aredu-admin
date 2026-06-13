<?php

namespace App\Exports;

use App\Exports\Concerns\StylesWorksheet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StyledRowsExport implements FromCollection, WithHeadings, WithEvents
{
    use StylesWorksheet;

    /** @var array<int, array<string, mixed>> */
    private array $rows;

    protected string $sheetTitle;

    /**
     * @param array<int, array<string, mixed>> $rows
     */
    public function __construct(array $rows, string $sheetTitle = 'Report')
    {
        $this->rows = $rows;
        $this->sheetTitle = $sheetTitle;
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
}
