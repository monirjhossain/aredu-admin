@php
    $applicationsByUniversity = $student->universitiesAppStatus->keyBy('university_id');
@endphp
@forelse ($student->universities as $university)
    @php
        $application = $applicationsByUniversity->get($university->id);
    @endphp
    <div class="uni-app-inline-row">
        <span class="uni-app-inline-label" title="{{ $university->post_title }}">{{ $university->post_title }}</span>
        @if ($application)
            @include('admin.students.partials.inline-status-select', [
                'updateUrl' => route('student.application.update-status', $application),
                'currentStatusId' => $application->status_id,
                'statuses' => $university_statuses,
                'tone' => \App\Support\StatusTone::forStatusId((int) $application->status_id),
                'compact' => true,
            ])
        @else
            <span class="text-muted small">—</span>
        @endif
    </div>
@empty
    <span class="text-muted">—</span>
@endforelse
