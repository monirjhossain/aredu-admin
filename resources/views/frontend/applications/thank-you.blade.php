@extends('layouts.public-application')

@section('title', $title)

@section('content')
<div class="hero">
    <div class="logo-wrap">
        <img src="{{ $logoUrl }}" alt="AR Education Logo">
    </div>
</div>

<div class="wizard-card">
    <div class="wizard-body text-center" style="padding: 48px 24px;">
        <div class="success-icon">✓</div>
        <h1 style="margin: 0 0 12px; font-size: 1.75rem; font-weight: 800;">Application Submitted!</h1>
        <p style="color: #6b7280; line-height: 1.7; margin: 0 0 24px; max-width: 420px; margin-inline: auto;">
            {{ session('success', 'Your application has been submitted successfully. Our team will contact you soon.') }}
        </p>

        @if (session('application_tracking_id'))
            <div class="tracking-card">
                <span class="tracking-label">Your Application Tracking ID</span>
                <div class="tracking-id-row">
                    <strong id="trackingIdValue">{{ session('application_tracking_id') }}</strong>
                    <button type="button" class="tracking-copy-btn" id="copyTrackingId" aria-label="Copy tracking ID">
                        Copy
                    </button>
                </div>
                <p class="tracking-note">Save this ID to track your application status with our team.</p>
            </div>
        @endif

        <a href="{{ session('inquiry_form_url', url('application-form')) }}" class="btn submit-btn" style="display: inline-block; width: auto; text-decoration: none; padding: 14px 28px; margin-top: 8px;">
            Submit Another Application
        </a>
    </div>
</div>

<p class="footer-note">
    <a href="https://areducation.net/" target="_blank" rel="noopener">Back to AR Education</a>
</p>
@endsection

@push('styles')
<style>
    .tracking-card {
        max-width: 420px;
        margin: 0 auto 24px;
        padding: 20px;
        border-radius: 16px;
        background: linear-gradient(180deg, #fff7ed 0%, #ffffff 100%);
        border: 1px solid rgba(249, 115, 22, 0.18);
        box-shadow: 0 12px 30px rgba(249, 115, 22, 0.08);
    }
    .tracking-label {
        display: block;
        font-size: 0.82rem;
        font-weight: 700;
        color: #9a3412;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .tracking-id-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .tracking-id-row strong {
        font-size: 1.15rem;
        font-weight: 800;
        color: #111827;
        letter-spacing: 0.03em;
    }
    .tracking-copy-btn {
        border: none;
        border-radius: 999px;
        padding: 8px 14px;
        font: inherit;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        color: #fff;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    }
    .tracking-copy-btn.copied {
        background: #059669;
    }
    .tracking-note {
        margin: 12px 0 0;
        font-size: 0.84rem;
        color: #6b7280;
        line-height: 1.6;
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const copyBtn = document.getElementById('copyTrackingId');
    const trackingValue = document.getElementById('trackingIdValue');
    if (!copyBtn || !trackingValue) return;

    copyBtn.addEventListener('click', function () {
        const text = trackingValue.textContent.trim();
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(showCopied);
        } else {
            const input = document.createElement('textarea');
            input.value = text;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showCopied();
        }
    });

    function showCopied() {
        copyBtn.textContent = 'Copied!';
        copyBtn.classList.add('copied');
        setTimeout(function () {
            copyBtn.textContent = 'Copy';
            copyBtn.classList.remove('copied');
        }, 2000);
    }
})();
</script>
@endpush
