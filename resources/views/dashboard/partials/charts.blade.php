@php
    $charts = $charts ?? [];
    $hasApplicationsChart = isset($charts['applications_by_status']);
    $hasStudentCharts = isset($charts['monthly_students']) || isset($charts['office_student_status']) || isset($charts['students_by_intake']);
    $hasBranchChart = isset($charts['students_by_branch']);
    $hasCounselorChart = isset($charts['counselor_wise_students']);
    $hasFinanceCharts = isset($charts['monthly_revenue']) || isset($charts['paid_vs_due']) || isset($charts['monthly_invoice_count']) || isset($charts['income_vs_expense']);
    $hasAnyChart = $hasApplicationsChart || $hasStudentCharts || $hasBranchChart || $hasCounselorChart || $hasFinanceCharts;
@endphp

@if ($hasAnyChart)
<div class="dashboard-charts-section mb-4 mb-md-5">
    <h4 class="dashboard-section-title mb-3 mb-md-4">Analytics</h4>
    <div class="row">
        @if ($hasApplicationsChart)
        <div class="col-12 col-lg-5 mb-3 mb-md-4">
            <div class="card card-custom dashboard-chart-card h-100">
                <div class="card-header border-0 pt-4 pt-lg-5 pb-0">
                    <h5 class="card-title font-weight-bolder mb-0">Applications by Status</h5>
                </div>
                <div class="card-body">
                    <div id="chart-applications-status" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($charts['monthly_students']))
        <div class="col-12 col-lg-{{ $hasApplicationsChart ? '7' : '12' }} mb-3 mb-md-4">
            <div class="card card-custom dashboard-chart-card h-100">
                <div class="card-header border-0 pt-4 pt-lg-5 pb-0">
                    <h5 class="card-title font-weight-bolder mb-0">New Students (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    <div id="chart-monthly-students" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($charts['office_student_status']))
        <div class="col-12 col-lg-5 mb-3 mb-md-4">
            <div class="card card-custom dashboard-chart-card h-100">
                <div class="card-header border-0 pt-4 pt-lg-5 pb-0">
                    <h5 class="card-title font-weight-bolder mb-0">Office Student Status</h5>
                </div>
                <div class="card-body">
                    <div id="chart-office-student-status" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($charts['students_by_intake']))
        <div class="col-12 col-lg-{{ isset($charts['office_student_status']) ? '7' : '12' }} mb-3 mb-md-4">
            <div class="card card-custom dashboard-chart-card h-100">
                <div class="card-header border-0 pt-4 pt-lg-5 pb-0">
                    <h5 class="card-title font-weight-bolder mb-0">Students by Intake</h5>
                </div>
                <div class="card-body">
                    <div id="chart-students-by-intake" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
        @endif

        @if ($hasBranchChart)
        <div class="col-12 col-lg-6 mb-3 mb-md-4">
            <div class="card card-custom dashboard-chart-card h-100">
                <div class="card-header border-0 pt-4 pt-lg-5 pb-0">
                    <h5 class="card-title font-weight-bolder mb-0">Students by Branch</h5>
                </div>
                <div class="card-body">
                    <div id="chart-students-by-branch" class="dashboard-chart dashboard-chart--horizontal" data-chart-horizontal="1"></div>
                </div>
            </div>
        </div>
        @endif

        @if ($hasCounselorChart)
        <div class="col-12 {{ $hasBranchChart ? 'col-lg-6' : '' }} mb-3 mb-md-4">
            <div class="card card-custom dashboard-chart-card h-100">
                <div class="card-header border-0 pt-4 pt-lg-5 pb-0">
                    <h5 class="card-title font-weight-bolder mb-0">Counselor-wise Students</h5>
                </div>
                <div class="card-body">
                    <div id="chart-counselor-wise-students" class="dashboard-chart dashboard-chart--horizontal" data-chart-horizontal="1"></div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($charts['monthly_revenue']))
        <div class="col-12 mb-3 mb-md-4">
            <div class="card card-custom dashboard-chart-card">
                <div class="card-header border-0 pt-4 pt-lg-5 pb-0">
                    <h5 class="card-title font-weight-bolder mb-0">Revenue — Paid (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    <div id="chart-monthly-revenue" class="dashboard-chart dashboard-chart--tall"></div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($charts['paid_vs_due']))
        <div class="col-12 col-lg-6 mb-3 mb-md-4">
            <div class="card card-custom dashboard-chart-card h-100">
                <div class="card-header border-0 pt-4 pt-lg-5 pb-0">
                    <h5 class="card-title font-weight-bolder mb-0">Paid vs Due (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    <div id="chart-paid-vs-due" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($charts['monthly_invoice_count']))
        <div class="col-12 col-lg-6 mb-3 mb-md-4">
            <div class="card card-custom dashboard-chart-card h-100">
                <div class="card-header border-0 pt-4 pt-lg-5 pb-0">
                    <h5 class="card-title font-weight-bolder mb-0">Monthly Invoice Count</h5>
                </div>
                <div class="card-body">
                    <div id="chart-monthly-invoice-count" class="dashboard-chart"></div>
                </div>
            </div>
        </div>
        @endif

        @if (isset($charts['income_vs_expense']))
        <div class="col-12 mb-3 mb-md-4">
            <div class="card card-custom dashboard-chart-card">
                <div class="card-header border-0 pt-4 pt-lg-5 pb-0">
                    <h5 class="card-title font-weight-bolder mb-0">Income vs Expense (Last 12 Months)</h5>
                </div>
                <div class="card-body">
                    <div id="chart-income-vs-expense" class="dashboard-chart dashboard-chart--tall"></div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    window.dashboardChartData = @json($charts);
</script>
@endif
