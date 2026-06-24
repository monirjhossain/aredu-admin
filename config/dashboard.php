<?php

return [

    /*
    |--------------------------------------------------------------------------
    | University application status groups (by name)
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | Role IDs (from roles table / RolesTableSeeder order)
    | 1 Super Admin, 2 Admin, 3 Manager, 4 Branch Manager,
    | 5 Account, 6 IT Officer, 7 Editor, 8 Counselor, 9 Student
    |--------------------------------------------------------------------------
    */
    'counselor_scope_role_ids' => [8],

    'branch_overview_role_ids' => [1, 2, 3, 4],

    'all_branches_role_ids' => [1, 2],

    /**
     * Which role_id can see each dashboard section.
     */
    'permissions' => [
        // KPI cards
        'kpi_students' => [1, 2, 3, 4, 8],
        'kpi_applications' => [1, 2, 3, 4, 8],
        'kpi_revenue' => [1, 2, 3, 4, 5],

        // Charts
        'charts_applications' => [1, 2, 3, 4, 8],
        'charts_students' => [1, 2, 3, 4, 8],
        'charts_branch' => [1, 2, 3],
        'charts_counselor' => [1, 2, 3, 4],
        'charts_finance' => [1, 2, 3, 4, 5],

        // Quick access tiles
        'quick_my_students' => [1, 2, 3, 4, 5, 6, 7, 8],
        'quick_all_students' => [1, 2, 3, 4],
        'quick_universities' => [1, 2, 3, 4],
        'quick_users' => [1, 2],
    ],

    'chart_months' => 12,
    'counselor_chart_limit' => 10,
    'intake_chart_limit' => 12,

];
