<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="form-group">
            <label for="type">Type</label>
            <input type="text" name="type" id="type" value="{{ old('type', $inquiry->type ?? 'Manual Inquiry') }}" class="form-control" placeholder="Inquiry type">
        </div>
    </div>
</div>

@include('shared.inquiry-application-fields', [
    'showBranchField' => true,
    'branches' => $branches ?? collect(),
    'requireCore' => false,
])
