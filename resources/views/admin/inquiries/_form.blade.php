<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="type">Type</label>
            <input type="text" name="type" id="type" value="{{ old('type', $inquiry->type ?? 'Manual Inquiry') }}" class="form-control" placeholder="Inquiry type">
        </div>
    </div>
    @if (!empty($inquiry->tracking_id))
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-group">
                <label>Tracking ID</label>
                <input type="text" value="{{ $inquiry->tracking_id }}" class="form-control bg-light" readonly>
            </div>
        </div>
    @endif
</div>

@include('shared.inquiry-application-fields', [
    'showBranchField' => true,
    'branches' => $branches ?? collect(),
    'requireCore' => false,
    'includeSubjectScript' => false,
])

@php
    $cscaExams = $cscaExams ?? collect();
    $selectedCscaIds = old('csca_subject_ids');
    if ($selectedCscaIds === null) {
        $savedNames = array_filter(array_map('trim', explode(',', $inquiry->csca_subjects ?? '')));
        $selectedCscaIds = $cscaExams->whereIn('name', $savedNames)->pluck('id')->all();
    }
@endphp

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="year">Year</label>
            <input type="text" name="year" id="year" value="{{ old('year', $inquiry->year ?? '') }}" class="form-control" placeholder="Passing year">
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="csca_subject_ids">CSCA Subjects</label>
            <select name="csca_subject_ids[]" id="csca_subject_ids" class="form-control inquiry-ms-select2" multiple data-placeholder="Select CSCA subjects">
                @foreach ($cscaExams as $exam)
                    <option value="{{ $exam->id }}" {{ in_array((int) $exam->id, array_map('intval', (array) $selectedCscaIds), true) ? 'selected' : '' }}>
                        {{ $exam->name }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">You can select multiple CSCA subjects</small>
        </div>
    </div>
</div>
