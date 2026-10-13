@extends('layouts.default')

@section('styles')
    <link href="{{ asset('css/admin/campaigns.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@php
    $totalClaims = $campaign->offers->sum('claims_count');
    $totalSpots = $campaign->offers->sum('max_claims');
@endphp
<div class="campaign-page">
    <div class="campaign-hero d-flex flex-wrap justify-content-between">
        <div>
            <div class="campaign-kicker">{{ strtoupper($campaign->status) }} CAMPAIGN</div>
            <h1>{{ $campaign->name }}</h1>
            <p>{{ $campaign->note ?: 'Track who scanned each QR and which discount they received.' }}</p>
        </div>
        <div class="mt-4 mt-md-0">
            <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-warning font-weight-bold mr-2">Edit</a>
            <a href="{{ route('campaigns.index') }}" class="btn btn-light font-weight-bold">All campaigns</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-4 mb-3"><div class="campaign-stat"><strong>{{ $campaign->offers->count() }}</strong><span>QR codes</span></div></div>
        <div class="col-md-4 mb-3"><div class="campaign-stat"><strong>{{ $totalClaims }}</strong><span>Students claimed</span></div></div>
        <div class="col-md-4 mb-3"><div class="campaign-stat"><strong>{{ max(0, $totalSpots - $totalClaims) }}</strong><span>Spots left</span></div></div>
    </div>

    <div class="row mb-6">
        @foreach($campaign->offers as $offer)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="qr-tile">
                    <div class="qr-amount">{{ number_format($offer->discount_amount) }} BDT</div>
                    <div class="font-weight-bold mb-2">{{ $offer->displayLabel() }}</div>
                    <img src="{{ $offer->qrImageUrl(360) }}" alt="QR {{ $offer->displayLabel() }}">
                    <div class="qr-meta mt-3">{{ $offer->claims_count }} / {{ $offer->max_claims }} claimed</div>
                    <div class="qr-meta mb-3">{{ $offer->remainingClaims() }} left · {{ $offer->is_active ? 'Active' : 'Paused' }}</div>
                    <a href="{{ $offer->publicUrl() }}" target="_blank" class="btn btn-sm btn-light-primary font-weight-bold mr-1">Open form</a>
                    <a href="{{ $offer->qrImageUrl(800) }}" target="_blank" class="btn btn-sm btn-light font-weight-bold">Download QR</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="campaign-card card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h3 class="card-title mb-0 font-weight-bolder">Claimed students</h3>
            <form method="GET" class="form-inline mt-3 mt-md-0">
                <select name="offer" class="form-control form-control-sm mr-2 mb-2">
                    <option value="">All QR codes</option>
                    @foreach($campaign->offers as $offer)
                        <option value="{{ $offer->id }}" {{ request('offer') == $offer->id ? 'selected' : '' }}>{{ number_format($offer->discount_amount) }}</option>
                    @endforeach
                </select>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm mr-2 mb-2" placeholder="Name, mobile, college, code">
                <button class="btn btn-sm btn-primary font-weight-bold mb-2">Search</button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-head-custom table-vertical-center mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>College</th>
                            <th>QR discount</th>
                            <th>Claim code</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($claims as $claim)
                            <tr>
                                <td>
                                    <div class="font-weight-bolder">{{ $claim->name }}</div>
                                    <div class="text-muted font-size-sm">{{ $claim->mobile }} · {{ $claim->created_at->format('d M Y, h:i A') }}</div>
                                </td>
                                <td>{{ $claim->college_name }}</td>
                                <td class="font-weight-bolder text-warning">{{ number_format($claim->discount_amount) }}</td>
                                <td><code>{{ $claim->claim_code }}</code></td>
                                <td><span class="claim-pill {{ $claim->status }}">{{ ucfirst($claim->status) }}</span></td>
                                <td class="text-right">
                                    <form method="POST" action="{{ route('campaigns.claims.mark', [$campaign, $claim]) }}">
                                        @csrf
                                        <button class="btn btn-sm {{ $claim->isUsed() ? 'btn-light' : 'btn-success' }} font-weight-bold">
                                            {{ $claim->isUsed() ? 'Mark available' : 'Mark used' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-8">No students have claimed a discount yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($claims->hasPages())
            <div class="card-footer">{{ $claims->links() }}</div>
        @endif
    </div>
</div>
@endsection
