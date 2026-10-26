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

@php
    $referenceValues = old('references');
    if ($referenceValues === null) {
        $referenceValues = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($inquiry->reference ?? '')))));
    }
@endphp

<div class="form-group mb-0" id="inquiry-references">
    <div id="inquiry-reference-rows">
        @foreach ($referenceValues as $referenceValue)
            <div class="input-group mb-2 inquiry-reference-row">
                <input type="text" name="references[]" value="{{ $referenceValue }}" class="form-control" placeholder="Reference name or phone">
                <div class="input-group-append">
                    <button type="button" class="btn btn-light-danger js-remove-reference">Remove</button>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-outline-danger btn-block" id="addInquiryReference">ADD Reference Field</button>
</div>

<script>
    (function () {
        var rows = document.getElementById('inquiry-reference-rows');
        var addButton = document.getElementById('addInquiryReference');
        if (!rows || !addButton || addButton.dataset.bound === '1') return;
        addButton.dataset.bound = '1';

        function bindRemove(button) {
            button.addEventListener('click', function () {
                var row = button.closest('.inquiry-reference-row');
                if (row) row.remove();
            });
        }

        rows.querySelectorAll('.js-remove-reference').forEach(bindRemove);

        addButton.addEventListener('click', function () {
            var row = document.createElement('div');
            row.className = 'input-group mb-2 inquiry-reference-row';
            row.innerHTML = '<input type="text" name="references[]" class="form-control" placeholder="Reference name or phone">'
                + '<div class="input-group-append"><button type="button" class="btn btn-light-danger js-remove-reference">Remove</button></div>';
            rows.appendChild(row);
            bindRemove(row.querySelector('.js-remove-reference'));
            row.querySelector('input').focus();
        });
    })();
</script>
