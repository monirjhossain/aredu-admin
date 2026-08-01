@extends('layouts.public-application')

@section('title', $title)

@section('content')
<div class="hero">
    <div class="logo-wrap">
        <img src="{{ $logoUrl }}" alt="AR Education Logo">
    </div>
    <h1>{{ $title }}</h1>
    <p>Interested in studying abroad? Fill out the application form below and our team will contact you shortly.</p>
    @if (!empty($branch))
        <p style="margin-top: 8px;"><strong>Branch:</strong> {{ $branch->name }}</p>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <h2>Application Details</h2>
    </div>
    <div class="card-body">
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('application-form.store', ['branch' => $branchId]) }}" method="POST" id="applicationForm">
            @csrf
            <input type="hidden" name="branch_id" value="{{ $branchId }}">

            @include('shared.inquiry-application-fields', [
                'wrapperClass' => 'form-grid',
                'columnClass' => 'form-group',
                'messageColumnClass' => 'form-group full-width',
                'groupClass' => null,
                'requireCore' => true,
                'includeSubjectScript' => false,
            ])

            <button type="submit" class="submit-btn" id="submitBtn">Submit Application</button>
        </form>
    </div>
</div>

<p class="footer-note">
    Powered by <a href="https://areducation.net/" target="_blank" rel="noopener">AR Education</a>
</p>
@endsection

@push('scripts')
@include('shared.inquiry-subject-script')
<script>
    (function () {
        const form = document.getElementById('applicationForm');
        const submitBtn = document.getElementById('submitBtn');
        if (!form || !submitBtn) return;

        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
        });
    })();
</script>
@endpush
