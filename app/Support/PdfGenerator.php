<?php

namespace App\Support;

use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\Response;

final class PdfGenerator
{
    /**
     * @param array<string, mixed> $options
     */
    public function download(string $html, string $filename, array $options = []): Response
    {
        $mpdf = new Mpdf(array_merge([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 8,
            'margin_bottom' => 8,
            'shrink_tables_to_fit' => 1,
            'basePath' => public_path(),
        ], $options));

        $mpdf->SetAutoPageBreak(true, 8);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
