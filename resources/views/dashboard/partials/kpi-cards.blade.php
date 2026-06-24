@php
    $c = $cards ?? [];
    $isCounselorView = $counselor_only ?? false;
@endphp

<div class="dashboard-kpi-section mb-4 mb-md-5">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 dashboard-kpi-section__head">
        <h4 class="dashboard-section-title mb-0">Business Overview</h4>
        @if ($isCounselorView)
            <span class="badge badge-light-primary">My assigned students</span>
        @else
            <span class="badge badge-light-success">Branch overview</span>
        @endif
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-md-4">
            <div class="dashboard-kpi-card dashboard-kpi-card--primary">
                <div class="dashboard-kpi-card__icon"><i class="fas fa-users"></i></div>
                <div class="dashboard-kpi-card__body">
                    <span class="dashboard-kpi-card__value">{{ number_format($c['total_students'] ?? 0) }}</span>
                    <span class="dashboard-kpi-card__label">Total Students</span>
                </div>
                <a href="{{ route('students.index') }}" class="dashboard-kpi-card__link">View list</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-md-4">
            <div class="dashboard-kpi-card dashboard-kpi-card--info">
                <div class="dashboard-kpi-card__icon"><i class="fas fa-user-plus"></i></div>
                <div class="dashboard-kpi-card__body">
                    <span class="dashboard-kpi-card__value">{{ number_format($c['new_students_month'] ?? 0) }}</span>
                    <span class="dashboard-kpi-card__label">New Students This Month</span>
                </div>
                <a href="{{ route('students.index') }}" class="dashboard-kpi-card__link">View students</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-md-4">
            <div class="dashboard-kpi-card dashboard-kpi-card--purple">
                <div class="dashboard-kpi-card__icon"><i class="fas fa-file-alt"></i></div>
                <div class="dashboard-kpi-card__body">
                    <span class="dashboard-kpi-card__value">{{ number_format($c['total_applications'] ?? 0) }}</span>
                    <span class="dashboard-kpi-card__label">Total Applications</span>
                </div>
                <a href="{{ route('students.index', ['universitylist' => 1]) }}" class="dashboard-kpi-card__link">University report</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-md-4">
            <div class="dashboard-kpi-card dashboard-kpi-card--warning">
                <div class="dashboard-kpi-card__icon"><i class="fas fa-clock"></i></div>
                <div class="dashboard-kpi-card__body">
                    <span class="dashboard-kpi-card__value">{{ number_format($c['pending_applications'] ?? 0) }}</span>
                    <span class="dashboard-kpi-card__label">Pending Applications</span>
                </div>
                <a href="{{ route('students.index', ['universitylist' => 1]) }}" class="dashboard-kpi-card__link">View applications</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-md-4">
            <div class="dashboard-kpi-card dashboard-kpi-card--success">
                <div class="dashboard-kpi-card__icon"><i class="fas fa-check-circle"></i></div>
                <div class="dashboard-kpi-card__body">
                    <span class="dashboard-kpi-card__value">{{ number_format($c['approved_applications'] ?? 0) }}</span>
                    <span class="dashboard-kpi-card__label">Approved Applications</span>
                </div>
                <a href="{{ route('students.index', ['universitylist' => 1]) }}" class="dashboard-kpi-card__link">View applications</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-md-4">
            <div class="dashboard-kpi-card dashboard-kpi-card--danger">
                <div class="dashboard-kpi-card__icon"><i class="fas fa-times-circle"></i></div>
                <div class="dashboard-kpi-card__body">
                    <span class="dashboard-kpi-card__value">{{ number_format($c['rejected_applications'] ?? 0) }}</span>
                    <span class="dashboard-kpi-card__label">Rejected Applications</span>
                </div>
                <a href="{{ route('students.index', ['universitylist' => 1]) }}" class="dashboard-kpi-card__link">View applications</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-md-4">
            <div class="dashboard-kpi-card dashboard-kpi-card--teal">
                <div class="dashboard-kpi-card__icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="dashboard-kpi-card__body">
                    <span class="dashboard-kpi-card__value">{{ number_format($c['total_revenue'] ?? 0, 2) }}</span>
                    <span class="dashboard-kpi-card__label">Total Revenue (Paid)</span>
                </div>
                <a href="{{ route('finance_entries.invoices.index') }}" class="dashboard-kpi-card__link">Invoices</a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-md-4">
            <div class="dashboard-kpi-card dashboard-kpi-card--orange">
                <div class="dashboard-kpi-card__icon"><i class="fas fa-chart-line"></i></div>
                <div class="dashboard-kpi-card__body">
                    <span class="dashboard-kpi-card__value">{{ number_format($c['revenue_this_month'] ?? 0, 2) }}</span>
                    <span class="dashboard-kpi-card__label">Revenue This Month</span>
                </div>
                <a href="{{ route('finance_entries.invoices.index') }}" class="dashboard-kpi-card__link">Invoices</a>
            </div>
        </div>
    </div>
</div>
