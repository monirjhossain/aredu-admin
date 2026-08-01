@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inquiries.index') }}">Inquiry List</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            @forelse ($branches as $branch)
                @php
                    $formUrl = \App\Support\BranchApplicationQr::formUrl($branch);
                    $qrImageUrl = \App\Support\BranchApplicationQr::imageUrl($branch, 220);
                    $logoUrl = \App\Support\BranchApplicationQr::logoUrl($branch);
                @endphp
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="{{ $logoUrl }}" alt="{{ $branch->name }}" style="max-height: 56px; margin-bottom: 12px;">
                            <h5 class="font-weight-bold mb-3">{{ $branch->name }}</h5>
                            <img src="{{ $qrImageUrl }}" alt="QR Code for {{ $branch->name }}" class="img-fluid mb-3" style="max-width: 220px;">
                            <p class="text-muted small mb-3" style="word-break: break-all;">{{ $formUrl }}</p>
                            <a href="{{ route('application-qr-codes.show', $branch->id) }}" class="btn btn-primary btn-sm mr-2" target="_blank">
                                <i class="fas fa-print"></i> Print / Download
                            </a>
                            <a href="{{ $formUrl }}" class="btn btn-light-primary btn-sm" target="_blank">
                                <i class="fas fa-external-link-alt"></i> Open Form
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning mb-0">No branches found.</div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
