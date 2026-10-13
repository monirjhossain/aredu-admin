@extends('layouts.default')

@section('styles')
    <link href="{{ asset('css/admin/campaigns.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="campaign-page">
    <div class="campaign-hero d-flex flex-wrap align-items-center justify-content-between">
        <div>
            <div class="campaign-kicker">FILE OPENING OFFERS</div>
            <h1>Campaigns</h1>
            <p>One QR per campaign. Set how many times each discount can be won, then track every claim.</p>
        </div>
        <a href="{{ route('campaigns.create') }}" class="btn btn-warning font-weight-bold mt-4 mt-md-0">
            <i class="fas fa-plus mr-1"></i> New Campaign
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse($campaigns as $campaign)
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="{{ route('campaigns.show', $campaign) }}" class="text-decoration-none">
                    <div class="campaign-card card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h4 class="font-weight-bolder text-dark mb-0">{{ $campaign->name }}</h4>
                                <span class="claim-pill {{ $campaign->status === 'active' ? 'available' : 'used' }}">{{ ucfirst($campaign->status) }}</span>
                            </div>
                            <div class="d-flex">
                                <div class="mr-5">
                                    <strong class="d-block text-dark">{{ $campaign->offers_count }}</strong>
                                    <span class="text-muted font-size-sm">Amounts</span>
                                </div>
                                <div>
                                    <strong class="d-block text-dark">{{ $campaign->claims_count }}</strong>
                                    <span class="text-muted font-size-sm">Students claimed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="campaign-card card">
                    <div class="card-body text-center py-10">
                        <h4 class="font-weight-bolder">No campaigns yet</h4>
                        <p class="text-muted">Start with one QR. Students randomly win 5,000, 10,000, 15,000 or 20,000.</p>
                        <a href="{{ route('campaigns.create') }}" class="btn btn-primary font-weight-bold">Create the first campaign</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
