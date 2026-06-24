@extends('layouts.default')

@section('content')
@php
    $access = $access ?? [];
    $sections = $access['sections'] ?? [];
@endphp

<div class="dashboard-page">
@include('dashboard.partials.kpi-cards', [
    'cards' => $cards ?? [],
    'access' => $access,
    'counselor_only' => $counselor_only ?? false,
])

@include('dashboard.partials.charts', [
    'charts' => $charts ?? [],
    'counselor_only' => $counselor_only ?? false,
])

@php
    $hasQuickAccess = ($sections['quick_my_students'] ?? false)
        || ($sections['quick_all_students'] ?? false)
        || ($sections['quick_universities'] ?? false)
        || ($sections['quick_users'] ?? false);
@endphp

@if ($hasQuickAccess)
<div class="dashboard-quick-stats mb-4">
    <h4 class="dashboard-section-title mb-3 mb-md-4">Quick Access</h4>
    <div class="row">
        @if ($sections['quick_my_students'] ?? false)
        <div class="col-6 col-md-3 mb-3">
            <div class="card-counter bg-danger">
                <span class="count-numbers">
                    <a href="{{ route('students.index') }}?counselor={{ auth()->user()->id }}&perpage=500">{{ $myStudents }}</a>
                </span>
                <span class="count-name">
                    <a href="{{ route('students.index') }}?counselor={{ auth()->user()->id }}&perpage=500">My Students</a>
                </span>
            </div>
        </div>
        @endif

        @if ($sections['quick_all_students'] ?? false)
        <div class="col-6 col-md-3 mb-3">
            <div class="card-counter bg-danger">
                <span class="count-numbers"><a href="{{ route('students.index') }}">{{ $allStudent }}</a></span>
                <span class="count-name"><a href="{{ route('students.index') }}">All Students</a></span>
            </div>
        </div>
        @endif

        @if ($sections['quick_universities'] ?? false)
        <div class="col-6 col-md-3 mb-3">
            <div class="card-counter bg-success">
                <span class="count-numbers"><a href="{{ route('universities.index') }}">{{ $universities }}</a></span>
                <span class="count-name"><a href="{{ route('universities.index') }}">Universities</a></span>
            </div>
        </div>
        @endif

        @if ($sections['quick_users'] ?? false)
        <div class="col-6 col-md-3 mb-3">
            <div class="card-counter bg-primary">
                <span class="count-numbers"><a href="{{ route('user-management.index') }}">{{ $total_user }}</a></span>
                <span class="count-name"><a href="{{ route('user-management.index') }}">Total Users</a></span>
            </div>
        </div>
        @endif
    </div>
</div>
@endif
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endsection

@section('styles')
    <link href="{{ asset('css/admin/dashboard.css') }}?v=2" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/dashboard-charts.js') }}?v=2"></script>
    <script src="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ asset('js/pages/widgets.js') }}"></script>
@endsection
