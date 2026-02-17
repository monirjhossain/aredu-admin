<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentSummaryExport implements FromView
{
    protected $entries;

    public function __construct($entries)
    {
        $this->entries = $entries;
    }

    public function view(): View
    {
        return view('admin.reports.export_student_summary', [
            'entries' => $this->entries
        ]);
    }
}
