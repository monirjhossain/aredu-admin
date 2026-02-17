<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExpenseSummaryExport implements FromView
{
    public $entries;
    public function __construct($entries)
    {
        $this->entries = $entries;
    }
    public function view(): View
    {
        return view('admin.reports.export_expense_summary', [
            'entries' => $this->entries
        ]);
    }
}
