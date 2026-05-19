@php
    $tone = \App\Support\UniversityApplicationStatusTone::forStatusId($application->status_id);
@endphp
<div class="uni-status-wrap status-tone-{{ $tone }}">
    <span class="uni-status-saving"><i class="fas fa-spinner fa-spin"></i></span>
    <select
        class="uni-app-status-select"
        data-url="{{ route('student.application.update-status', $application) }}"
        data-original="{{ $application->status_id }}"
    >
        @foreach ($university_statuses as $status)
            <option value="{{ $status->id }}" {{ (int) $application->status_id === (int) $status->id ? 'selected' : '' }}>
                {{ $status->name }}
            </option>
        @endforeach
    </select>
</div>
