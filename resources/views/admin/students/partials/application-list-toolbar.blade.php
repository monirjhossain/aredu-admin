<form method="GET" action="{{ route('students.index') }}" class="d-flex flex-wrap align-items-center mb-3 w-100">
    <input type="hidden" name="universitylist" value="1">
    <input type="hidden" name="applicationlist" value="{{ $university->id }}">
    <select name="application_status" class="form-control mr-2 mb-2" style="max-width: 240px;">
        <option value="">All application statuses</option>
        @foreach ($university_statuses as $status)
            <option value="{{ $status->id }}" {{ (int) ($application_status_filter ?? 0) === (int) $status->id ? 'selected' : '' }}>
                {{ $status->name }}
            </option>
        @endforeach
    </select>
    <select name="subject" class="form-control mr-2 mb-2" style="max-width: 240px;">
        <option value="">All subjects</option>
        @foreach ($subjects ?? [] as $item)
            <option value="{{ $item->id }}" {{ (int) ($subject_filter ?? 0) === (int) $item->id ? 'selected' : '' }}>
                {{ $item->name }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-primary mr-2 mb-2">
        <i class="la la-search"></i> Filter
    </button>
    <a href="{{ $reset_url ?? route('students.index', ['universitylist' => 1, 'applicationlist' => $university->id]) }}" class="btn btn-light-primary mr-2 mb-2">
        <i class="la la-undo"></i> Reset
    </a>
    <a href="{{ $export_url ?? route('students.index', array_merge($list_route_query ?? ['universitylist' => 1, 'applicationlist' => $university->id], ['export' => 'yes'])) }}" class="btn btn-success ml-auto mb-2" target="_blank">
        <i class="la la-download"></i> Export
    </a>
</form>
