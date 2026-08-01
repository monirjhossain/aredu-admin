<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Inquiry\Inquiry;

class ApplicationTrackingIdGenerator
{
    public function generate(Branch $branch): string
    {
        $branchShort = strtoupper((string) ($branch->short_name ?: 'BR' . $branch->id));
        $year = date('Y');
        $prefix = 'APP-' . $branchShort . '-' . $year . '-';

        $lastTrackingId = Inquiry::withTrashed()
            ->where('tracking_id', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('tracking_id');

        $nextSerial = 1;

        if ($lastTrackingId) {
            preg_match('/-(\d+)$/', $lastTrackingId, $matches);
            $nextSerial = ((int) ($matches[1] ?? 0)) + 1;
        }

        return $prefix . str_pad((string) $nextSerial, 4, '0', STR_PAD_LEFT);
    }
}
