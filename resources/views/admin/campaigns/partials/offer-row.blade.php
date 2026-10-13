<div class="offer-row">
    <input type="hidden" name="offers[{{ $index }}][id]" value="{{ $offer->id ?? '' }}">
    <div class="row align-items-end">
        <div class="col-md-4">
            <label>Label</label>
            <input type="text" name="offers[{{ $index }}][label]" class="form-control" value="{{ $offer->label ?? '' }}" placeholder="5,000 Discount">
        </div>
        <div class="col-md-3">
            <label>Discount (BDT)</label>
            <input type="number" min="1" name="offers[{{ $index }}][discount_amount]" class="form-control" value="{{ $offer->discount_amount ?? '' }}" required>
        </div>
        <div class="col-md-2">
            <label>Student limit</label>
            <input type="number" min="1" name="offers[{{ $index }}][max_claims]" class="form-control" value="{{ $offer->max_claims ?? 500 }}" required>
        </div>
        <div class="col-md-2">
            <label class="d-block">Active</label>
            <span class="switch switch-sm switch-icon">
                <label class="mb-0">
                    <input type="checkbox" name="offers[{{ $index }}][is_active]" value="1" {{ !isset($offer->is_active) || $offer->is_active ? 'checked' : '' }}>
                    <span></span>
                </label>
            </span>
        </div>
        <div class="col-md-1 text-right">
            <button type="button" class="btn btn-sm btn-light-danger js-remove-offer">Remove</button>
        </div>
    </div>
</div>
