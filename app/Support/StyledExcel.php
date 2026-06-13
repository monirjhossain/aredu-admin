<?php

namespace App\Support;

use App\Exports\StyledRowsExport;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class StyledExcel
{
    /**
     * @param array<int, array<string, mixed>> $rows
     */
    public static function downloadRows(array $rows, string $filename, string $sheetTitle = 'Report')
    {
        $xlsxFilename = self::xlsxFilename($filename);

        try {
            return Excel::download(
                new StyledRowsExport($rows, $sheetTitle),
                $xlsxFilename
            );
        } catch (Throwable $e) {
            report($e);

            return self::streamCsv($rows, self::csvFilename($xlsxFilename));
        }
    }

    public static function download($export, string $filename)
    {
        $xlsxFilename = self::xlsxFilename($filename);

        try {
            return Excel::download($export, $xlsxFilename);
        } catch (Throwable $e) {
            report($e);

            throw $e;
        }
    }

    public static function xlsxFilename(string $filename): string
    {
        $filename = preg_replace('/\.csv$/i', '', $filename);

        if (!preg_match('/\.xlsx$/i', $filename)) {
            $filename .= '.xlsx';
        }

        return $filename;
    }

    public static function csvFilename(string $filename): string
    {
        return preg_replace('/\.xlsx$/i', '.csv', self::xlsxFilename($filename));
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     */
    public static function streamCsv(array $rows, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $callback = static function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            if ($rows !== []) {
                fputcsv($handle, array_keys($rows[0]));
                foreach ($rows as $row) {
                    fputcsv($handle, array_values($row));
                }
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
