<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IncomeSummaryExport implements FromView
{
    public $entries;
    public function __construct($entries)
    {
        $this->entries = $entries;
    }
    public function view(): View
    {
        return view('admin.reports.export_income_summary', [
            'entries' => $this->entries
        ]);
    }
}
