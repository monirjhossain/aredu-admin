<?php

return [

    /*
    |--------------------------------------------------------------------------
    | University application status groups (by name)
    |--------------------------------------------------------------------------
    | Names are matched against university_statuses.name (case-insensitive).
    | Update when new statuses are added in admin.
    */
    'application_status_groups' => [
        'pending' => [
            'File Processing',
            'File Submitted',
            'Application Fee Pending',
            'Application Fee Paid',
            'Pass Initial Review',
            'Interview Pending',
            'Interview Done',
            'Admission Confirmation Fee Pending',
        ],
        'approved' => [
            'Pre Admitted',
            'Admission Confirmation Fee Paid',
            'Admitted',
            'JW Issued',
            'File Processing Completed',
        ],
        'rejected' => [
            'Reject',
            'Rejected',
        ],
    ],

    /** Role IDs that see full branch stats (not counselor-only). */
    'branch_overview_role_ids' => [1, 2, 3],

    /** Months of history for trend charts. */
    'chart_months' => 12,

    /** Max counselors shown on counselor-wise chart. */
    'counselor_chart_limit' => 10,

    /** Max intake labels on intake chart. */
    'intake_chart_limit' => 12,

];
