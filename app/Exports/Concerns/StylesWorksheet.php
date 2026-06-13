<?php

namespace App\Exports\Concerns;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

trait StylesWorksheet
{
    protected function worksheetTitle(): string
    {
        if (property_exists($this, 'sheetTitle') && is_string($this->sheetTitle) && $this->sheetTitle !== '') {
            return $this->sheetTitle;
        }

        return 'Report';
    }

    /**
     * @return array<class-string, callable>
     */
    protected function styledSheetEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = (int) $sheet->getHighestRow();

                if ($highestRow < 1 || $highestColumn === '') {
                    return;
                }

                $title = preg_replace('/[\\\\\\/*?:\\[\\]]/', '', $this->worksheetTitle());
                $sheet->setTitle(substr($title !== '' ? $title : 'Report', 0, 31));

                $headerRange = 'A1:' . $highestColumn . '1';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11,
                        'name' => 'Calibri',
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                if ($highestRow > 1) {
                    $dataRange = 'A2:' . $highestColumn . $highestRow;
                    $sheet->getStyle($dataRange)->applyFromArray([
                        'font' => [
                            'size' => 10,
                            'name' => 'Calibri',
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'D9D9D9'],
                            ],
                        ],
                    ]);

                    for ($row = 2; $row <= $highestRow; $row++) {
                        if ($row % 2 === 0) {
                            $sheet->getStyle('A' . $row . ':' . $highestColumn . $row)->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['rgb' => 'F2F7FB'],
                                ],
                            ]);
                        }
                    }
                }

                $fullRange = 'A1:' . $highestColumn . $highestRow;
                $sheet->getStyle($fullRange)->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['rgb' => '1F4E78'],
                        ],
                    ],
                ]);

                $sheet->setAutoFilter($headerRange);
                $sheet->freezePane('A2');

                foreach (range('A', $highestColumn) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }

    public function registerEvents(): array
    {
        return $this->styledSheetEvents();
    }
}
