@extends('layouts.default')

@section('styles')
    <link href="{{ asset('css/admin/campaigns.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="campaign-page">
    <div class="campaign-hero">
        <div class="campaign-kicker">CAMPAIGN SETUP</div>
        <h1>{{ $title }}</h1>
        <p>Everyone scans the same QR. Each row is one discount, and how many times that amount can be won.</p>
    </div>

    <form method="POST" action="{{ $campaign->exists ? route('campaigns.update', $campaign) : route('campaigns.store') }}">
        @csrf
        @if($campaign->exists) @method('PUT') @endif
        <div class="campaign-card card mb-4">
            <div class="card-body">
                @include('includes.errors')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Campaign name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $campaign->name) }}" required placeholder="College Fair 2026">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ old('status', $campaign->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="closed" {{ old('status', $campaign->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-0">
                            <label>Note</label>
                            <textarea name="note" rows="2" class="form-control" placeholder="Optional note for staff">{{ old('note', $campaign->note) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="campaign-card card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0 font-weight-bolder">Discount amounts</h3>
                <button type="button" class="btn btn-sm btn-light-primary font-weight-bold" id="addOfferRow">Add QR</button>
            </div>
            <div class="card-body" id="offerRows">
                @php
                    $posted = old('offers');
                    $rows = $posted ?: (is_array($offers) ? $offers : $offers->toArray());
                @endphp
                @foreach($rows as $index => $offer)
                    @include('admin.campaigns.partials.offer-row', ['index' => $index, 'offer' => (object) $offer])
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary font-weight-bold px-8">Save Campaign</button>
        <a href="{{ $campaign->exists ? route('campaigns.show', $campaign) : route('campaigns.index') }}" class="btn btn-light font-weight-bold ml-2">Cancel</a>
    </form>
</div>

<template id="offerRowTemplate">
    @include('admin.campaigns.partials.offer-row', ['index' => '__INDEX__', 'offer' => (object) ['label' => '', 'discount_amount' => '', 'max_claims' => 500, 'is_active' => true]])
</template>
@endsection

@section('scripts')
<script>
    document.getElementById('addOfferRow').addEventListener('click', function () {
        var wrap = document.getElementById('offerRows');
        var index = wrap.querySelectorAll('.offer-row').length;
        var html = document.getElementById('offerRowTemplate').innerHTML.replace(/__INDEX__/g, index);
        wrap.insertAdjacentHTML('beforeend', html);
    });
    document.getElementById('offerRows').addEventListener('click', function (event) {
        if (!event.target.classList.contains('js-remove-offer')) return;
        var rows = document.querySelectorAll('#offerRows .offer-row');
        if (rows.length < 2) return;
        event.target.closest('.offer-row').remove();
    });
</script>
@endsection
