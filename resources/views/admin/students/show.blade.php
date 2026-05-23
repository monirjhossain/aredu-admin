@extends('layouts.default')

@section('styles')
    <link href="{{ asset('css/admin/student-profile.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/admin/inline-status.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="student-show-subheader d-flex flex-wrap align-items-center justify-content-between">
    <div>
        <h1 class="font-weight-bolder text-dark mb-1">{{ $student->full_name }}</h1>
        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('students.index') }}" class="text-muted">Students</a></li>
            <li class="breadcrumb-item text-muted">Profile</li>
        </ul>
    </div>
    <div class="d-flex flex-wrap mt-3 mt-md-0">
        <a href="{{ route('students.index') }}" class="btn btn-light-primary font-weight-bold mr-2 mb-2">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
        <a href="{{ route('student.invoices.index') }}?student={{ $student->id }}" class="btn btn-light-info font-weight-bold mr-2 mb-2">
            <i class="fas fa-file-invoice mr-1"></i> Invoices
        </a>
        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary font-weight-bold mb-2">
            <i class="fas fa-edit mr-1"></i> Edit Student
        </a>
    </div>
</div>

<div class="card card-custom gutter-b student-show-hero">
    <div class="card-body py-8">
        <div class="d-flex flex-wrap align-items-center">
            <div class="symbol symbol-90 symbol-circle symbol-light-primary mr-6 mb-4 mb-md-0">
                <span class="symbol-label text-primary">{{ $initials ?: 'ST' }}</span>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex flex-wrap align-items-center mb-2">
                    <h2 class="font-weight-bolder text-dark mb-0 mr-4">{{ $student->full_name }}</h2>
                    @can('updateOfficeStatus', $student)
                        <div
                            data-inline-status-root
                            data-csrf="{{ csrf_token() }}"
                            data-status-type="office"
                            data-status-tones='@json($status_tone_map)'
                        >
                            @include('admin.students.partials.inline-status-select', [
                                'updateUrl' => route('students.update-office-status', $student),
                                'currentStatusId' => $student->status,
                                'statuses' => $student_statuses,
                                'tone' => $officeTone,
                            ])
                        </div>
                    @else
                        <span class="label label-lg label-light-primary label-inline font-weight-bold">
                            {{ $student->student_status->name ?? '—' }}
                        </span>
                    @endcan
                </div>
                <div class="student-meta d-flex flex-wrap">
                    @if($student->email)
                        <span class="mr-5 mb-1"><i class="fas fa-envelope"></i>{{ $student->email }}</span>
                    @endif
                    @if($student->mobile_no)
                        <span class="mr-5 mb-1"><i class="fas fa-phone"></i>{{ $student->mobile_no }}</span>
                    @endif
                    @if($student->preferred_country)
                        <span class="mr-5 mb-1"><i class="fas fa-globe"></i>{{ $student->preferred_country }}</span>
                    @endif
                    @if($student->intake)
                        <span class="mb-1"><i class="fas fa-calendar-alt"></i>{{ $student->intake }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-6">
        <div class="card card-custom student-stat-card gutter-b">
            <div class="card-body py-5 text-center">
                <div class="stat-value text-primary">{{ $student->subjects->count() }}</div>
                <div class="stat-label">Subjects</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom student-stat-card gutter-b">
            <div class="card-body py-5 text-center">
                <div class="stat-value text-success">{{ $student->universities->count() }}</div>
                <div class="stat-label">Universities</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom student-stat-card gutter-b">
            <div class="card-body py-5 text-center">
                <div class="stat-value text-warning">{{ $applicationCount }}</div>
                <div class="stat-label">Applications</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card card-custom student-stat-card gutter-b">
            <div class="card-body py-5 text-center">
                <div class="stat-value text-info">{{ $student->documents->count() }}</div>
                <div class="stat-label">Documents</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-xl-8">
    <div class="card card-custom student-info-card gutter-b">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user text-primary mr-2"></i>Personal Information</h3>
        </div>
        <div class="card-body pt-2 pb-4">
            @foreach($personalDetails as $detail)
                <div class="student-detail-row">
                    <div class="student-detail-label">{{ $detail['label'] }}</div>
                    <div class="student-detail-value">{{ $detail['value'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card card-custom student-info-card gutter-b">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-graduation-cap text-success mr-2"></i>Academic & CSCA</h3>
        </div>
        <div class="card-body pt-4 pb-6">
<div class="academic-basic-grid mb-6">
    <div class="academic-basic-item">
        <span class="academic-basic-label">Passport</span>
        <span class="academic-basic-value">{{ $student->passport ?: '—' }}</span>
    </div>
    <div class="academic-basic-item">
        <span class="academic-basic-label">Last Education</span>
        <span class="academic-basic-value">{{ $student->last_education ?: '—' }}</span>
    </div>
    <div class="academic-basic-item">
        <span class="academic-basic-label">Language Proficiency</span>
        <span class="academic-basic-value">{{ $student->language_cer ?: '—' }}</span>
    </div>
    <div class="academic-basic-item">
        <span class="academic-basic-label">Study Level</span>
        <span class="academic-basic-value">{{ $student->study_level ?: '—' }}</span>
    </div>
</div>

<div class="csca-panel">
    <div class="csca-panel-head d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <span class="csca-panel-title">CSCA Examination</span>
            <span class="d-block text-muted font-size-sm mt-1">Structured exam scores and background</span>
        </div>
        <span class="label label-lg label-inline font-weight-bold {{ $csca['has_csca'] ? 'label-light-success' : 'label-light-dark' }}">
            {{ $csca['has_csca'] ? 'Has CSCA' : 'No CSCA' }}
        </span>
    </div>

    @if($csca['has_exam_rows'])
        <div class="csca-score-grid">
            @foreach($student->studentCscaExams as $cscaResult)
                <div class="csca-score-card">
                    <span class="csca-score-exam">{{ $cscaResult->cscaExam->name ?? 'CSCA Exam' }}</span>
                    <span class="csca-score-result">{{ $cscaResult->result ?: '—' }}</span>
                </div>
            @endforeach
        </div>
    @elseif(!empty($csca['legacy_scores']))
        <div class="csca-score-grid">
            @foreach($csca['legacy_scores'] as $legacy)
                <div class="csca-score-card">
                    <span class="csca-score-exam">{{ $legacy['exam'] }}</span>
                    <span class="csca-score-result">{{ $legacy['result'] }}</span>
                </div>
            @endforeach
        </div>
    @elseif($csca['show_empty'])
        <div class="csca-empty text-muted font-size-sm">
            <i class="fas fa-info-circle mr-1"></i> No CSCA examination records on file.
        </div>
    @endif
</div>

@if($student->note)
    <div class="academic-remark mt-6">
        <span class="academic-basic-label d-block mb-2">Remark</span>
        <div class="academic-remark-body">
            @php
                $rawNote = trim(preg_replace('/\s+/u', ' ', strip_tags($student->note)));
                $words = preg_split('/\s+/u', $rawNote, -1, PREG_SPLIT_NO_EMPTY);
                $isTruncated = count($words) > 30;
                $previewText = $isTruncated ? implode(' ', array_slice($words, 0, 30)) : $rawNote;
            @endphp
            <div class="student-note-text">
                <div class="student-note-preview">{!! nl2br(e($previewText)) !!}</div>
                @if($isTruncated)
                    <div class="student-note-full d-none">{!! nl2br(e($student->note)) !!}</div>
                    <a href="#" class="font-weight-bold student-note-toggle">Load more</a>
                @endif
            </div>
        </div>
    </div>
@endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-custom student-info-card gutter-b">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-male text-info mr-2"></i>Father</h3>
                </div>
                <div class="card-body pt-2 pb-4">
                    @foreach($fatherDetails as $detail)
                        <div class="student-detail-row">
                            <div class="student-detail-label">{{ $detail['label'] }}</div>
                            <div class="student-detail-value">{{ $detail['value'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-custom student-info-card gutter-b">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-female text-danger mr-2"></i>Mother</h3>
                </div>
                <div class="card-body pt-2 pb-4">
                    @foreach($motherDetails as $detail)
                        <div class="student-detail-row">
                            <div class="student-detail-label">{{ $detail['label'] }}</div>
                            <div class="student-detail-value">{{ $detail['value'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-4">
    <div class="card card-custom student-info-card gutter-b">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users text-primary mr-2"></i>Assigned Team</h3>
        </div>
        <div class="card-body pt-2 pb-4">
            @foreach($teamDetails as $detail)
                <div class="student-detail-row">
                    <div class="student-detail-label">{{ $detail['label'] }}</div>
                    <div class="student-detail-value">{{ $detail['value'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card card-custom student-info-card gutter-b">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-book text-success mr-2"></i>Subjects</h3>
        </div>
        <div class="card-body student-tag-list">
            @forelse($student->subjects as $sub)
                <span class="label label-light-primary label-inline">{{ $sub->name }}</span>
            @empty
                <span class="text-muted">No subjects assigned</span>
            @endforelse
        </div>
    </div>

    <div class="card card-custom student-info-card gutter-b">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-university text-warning mr-2"></i>Universities</h3>
        </div>
        <div class="card-body student-tag-list">
            @forelse($student->universities as $uni)
                <span class="label label-light-success label-inline">{{ $uni->post_title }}</span>
            @empty
                <span class="text-muted">No universities selected</span>
            @endforelse
        </div>
    </div>

    <div class="card card-custom student-info-card gutter-b">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-folder-open text-info mr-2"></i>Documents</h3>
        </div>
        <div class="card-body student-tag-list">
            @forelse($student->documents as $doc)
                <span class="label label-light-info label-inline">{{ $doc->name }}</span>
            @empty
                <span class="text-muted">No documents uploaded</span>
            @endforelse
        </div>
    </div>
</div>
</div>
@can('updateApplicationStatus', $student)
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus-circle text-primary mr-2"></i>New Application</h3>
    </div>
    <form action="{{ route($url . '.store') }}" method="POST">
        @csrf
        <input type="hidden" name="student_id" value="{{ $student->id }}">
        <input type="hidden" name="status_id" value="1">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Subject</label>
                        <select name="subject_id" class="form-control form-control-solid" required>
                            <option value="">— Select subject —</option>
                            @foreach($student->subjects as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">University</label>
                        <select name="university_id" class="form-control form-control-solid" required>
                            <option value="">— Select university —</option>
                            @foreach($student->universities as $item)
                                <option value="{{ $item->id }}">{{ $item->post_title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button type="submit" class="btn btn-primary font-weight-bold">
                <i class="fas fa-paper-plane mr-1"></i> Add Application
            </button>
        </div>
    </form>
</div>
@endcan
<div class="d-flex align-items-center justify-content-between mb-4">
    <h3 class="font-weight-bolder text-dark mb-0">University Applications</h3>
    <span class="label label-lg label-light-primary label-inline font-weight-bold">{{ $applicationCount }} total</span>
</div>

<div data-inline-status-root data-csrf="{{ csrf_token() }}" data-status-type="application" data-status-tones='@json($status_tone_map)'>
    @forelse($student->universitiesAppStatus as $item)
        @php $appTone = $applicationTones[$item->id] ?? 'primary'; @endphp
<div class="card card-custom application-card gutter-b">
    <div class="card-header flex-wrap">
        <div class="card-title flex-column align-items-start">
            <h3 class="card-label font-weight-bolder text-dark mb-1">
                {{ optional($item->university)->post_title ?? 'University' }}
            </h3>
            <span class="text-muted font-size-sm">
                {{ optional($item->subject)->name ?? '—' }}
                @if($item->application_no)
                    · App #{{ $item->application_no }}
                @endif
            </span>
        </div>
        <div class="card-toolbar d-flex flex-wrap align-items-center">
            @can('updateApplicationStatus', $student)
                @include('admin.students.partials.inline-status-select', [
                    'updateUrl' => route('student.application.update-status', $item),
                    'currentStatusId' => $item->status_id,
                    'statuses' => $university_statuses,
                    'tone' => $appTone,
                    'wrapClass' => 'mr-3 mb-2',
                ])
            @else
                <span class="label label-lg label-light-{{ $appTone }} label-inline font-weight-bold mr-3 mb-2">
                    {{ optional($item->statusUni)->name ?? '—' }}
                </span>
            @endcan
            @can('updateApplicationStatus', $student)
                <a href="{{ route($url . '.edit', $item->id) }}" class="btn btn-sm btn-light-primary font-weight-bold mr-2 mb-2" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <form id="deleteForm{{ $item->id }}" action="{{ route($url . '.destroy', $item) }}" class="d-inline-block mb-2" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDeleteStudentApplication({{ $item->id }})" class="btn btn-sm btn-light-danger font-weight-bold" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            @endcan
        </div>
    </div>
    <div class="card-body pt-4">
        @if($item->statusAll->isNotEmpty())
            <div class="font-weight-bold text-muted font-size-sm mb-3 text-uppercase">Status progress</div>
            <div class="app-status-track">
                @foreach($item->statusAll as $key => $st)
                    <div class="app-status-step {{ (int) $st->active === 1 ? 'is-active' : '' }}">
                        <span class="step-dot">{{ $key + 1 }}</span>
                        <span class="step-name">{{ optional($st->statusUni)->name }}</span>
                        @if($st->created_at)
                            <span class="step-date">{{ $st->created_at }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <div class="row">
            @can('viewPortalCredentials', $student)
                <div class="col-lg-6 mb-4">
                    <div class="font-weight-bold text-muted font-size-sm mb-2 text-uppercase">Portal credentials</div>
                    <div class="bg-light rounded p-4 font-size-sm">
                        <div><strong>Link:</strong> {{ optional($item->university)->getMeta('link') ?: '—' }}</div>
                        @if((int) ($item->portal_type ?? 1) === 1)
                            <div><strong>User ID:</strong> {{ optional($item->university)->getMeta('user') ?: '—' }}</div>
                            <div><strong>Password:</strong> {{ optional($item->university)->getMeta('password') ?: '—' }}</div>
                        @else
                            <div><strong>User ID:</strong> {{ $item->login_id ?: '—' }}</div>
                            <div><strong>Password:</strong> {{ $item->login_password ?: '—' }}</div>
                        @endif
                    </div>
                </div>
            @endcan
            <div class="col-lg-{{ $canViewCredentials ? '6' : '12' }} mb-4">
                <div class="font-weight-bold text-muted font-size-sm mb-2 text-uppercase">Notes</div>
                <div class="bg-light rounded p-4 font-size-sm">
                    <div class="mb-2"><strong>Note:</strong> {{ $item->note ?: '—' }}</div>
                    <div><strong>Remarks:</strong> {{ $item->remarks ?: '—' }}</div>
                </div>
            </div>
        </div>

        <div class="font-weight-bold text-muted font-size-sm mb-3 text-uppercase">Documents</div>
        <div class="d-flex flex-wrap">
            @if($item->admission_notice)
                <a href="{{ asset('storage/student_applications/' . $item->admission_notice) }}" target="_blank" class="btn btn-light app-file-btn mr-2 mb-2" title="Admission Notice">
                    <i class="fas fa-file-pdf text-danger mr-1"></i> Admission
                </a>
            @endif
            @if($item->jw_copy)
                <a href="{{ asset('storage/student_applications/' . $item->jw_copy) }}" target="_blank" class="btn btn-light app-file-btn mr-2 mb-2" title="JW Copy">
                    <i class="fas fa-file-pdf text-primary mr-1"></i> JW Copy
                </a>
            @endif
            @if($item->visa_copy)
                <a href="{{ asset('storage/student_applications/' . $item->visa_copy) }}" target="_blank" class="btn btn-light app-file-btn mr-2 mb-2" title="Visa Copy">
                    <i class="fas fa-file-pdf text-success mr-1"></i> Visa
                </a>
            @endif
            @if(!$item->admission_notice && !$item->jw_copy && !$item->visa_copy)
                <span class="text-muted font-size-sm">No files uploaded</span>
            @endif
        </div>
    </div>
</div>
    @empty
        <div class="card card-custom gutter-b">
            <div class="card-body text-center py-10">
                <i class="fas fa-inbox text-muted mb-4" style="font-size: 3rem;"></i>
                <p class="text-muted font-weight-bold mb-0">No university applications yet.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/inline-status-select.js') }}"></script>
<script src="{{ asset('js/admin/student-show.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.student-note-toggle').forEach(function (toggle) {
            toggle.addEventListener('click', function (event) {
                event.preventDefault();
                var wrapper = event.currentTarget.closest('.student-note-text');
                if (!wrapper) {
                    return;
                }
                var preview = wrapper.querySelector('.student-note-preview');
                var full = wrapper.querySelector('.student-note-full');
                if (!preview || !full) {
                    return;
                }
                var isExpanded = wrapper.classList.contains('is-expanded');
                if (isExpanded) {
                    preview.classList.remove('d-none');
                    full.classList.add('d-none');
                    event.currentTarget.textContent = 'Load more';
                    wrapper.classList.remove('is-expanded');
                } else {
                    preview.classList.add('d-none');
                    full.classList.remove('d-none');
                    event.currentTarget.textContent = 'Show less';
                    wrapper.classList.add('is-expanded');
                }
            });
        });
    });
</script>
@endsection

