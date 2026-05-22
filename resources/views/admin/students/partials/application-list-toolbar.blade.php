<form method="GET" action="{{ route('students.index') }}" class="d-flex flex-wrap align-items-center mb-3 w-100">
    <input type="hidden" name="universitylist" value="1">
    <input type="hidden" name="applicationlist" value="{{ $university->id }}">
    <select name="application_status" class="form-control mr-2" style="max-width: 240px;">
        <option value="">All application statuses</option>
        @foreach ($university_statuses as $status)
            <option value="{{ $status->id }}" {{ (int) ($application_status_filter ?? 0) === (int) $status->id ? 'selected' : '' }}>
                {{ $status->name }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-primary mr-2">
        <i class="la la-search"></i> Filter
    </button>
    <a href="{{ route('students.index', ['universitylist' => 1, 'applicationlist' => $university->id]) }}" class="btn btn-light-primary mr-2">
        <i class="la la-undo"></i> Reset
    </a>
    <a href="{{ route('students.index', array_filter([
        'universitylist' => 1,
        'applicationlist' => $university->id,
        'export' => 'yes',
        'application_status' => $application_status_filter ?? null,
    ], function ($value) { return filled($value); })) }}" class="btn btn-success ml-auto" target="_blank">
        <i class="la la-download"></i> Export
    </a>
</form>
