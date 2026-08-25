@extends('layouts.public-application')

@section('title', $title)

@php
    $whatsappUrl = session('whatsapp_group_url');
    $isCscaThankYou = session('inquiry_form_type') === 'csca'
        || strpos((string) session('inquiry_form_url'), '/inquiry-form/csca') !== false;
    if ($isCscaThankYou && !$whatsappUrl) {
        $whatsappUrl = config('inquiry.public_forms.csca.whatsapp', 'https://chat.whatsapp.com/GfdWN13HhmEBrZHDVGwqcX');
    }
@endphp

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

        @if ($whatsappUrl)
            <a class="wa-btn" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M20.52 3.48A11.78 11.78 0 0 0 12.04 0C5.5 0 .16 5.34.16 11.88c0 2.1.55 4.15 1.6 5.96L0 24l6.3-1.65a11.86 11.86 0 0 0 5.74 1.46h.01c6.54 0 11.88-5.34 11.88-11.88 0-3.17-1.24-6.16-3.41-8.45zM12.05 21.15h-.01a9.86 9.86 0 0 1-5.02-1.38l-.36-.21-3.74.98 1-3.64-.24-.37a9.84 9.84 0 0 1-1.51-5.25c0-5.43 4.42-9.85 9.86-9.85 2.63 0 5.1 1.02 6.96 2.88a9.79 9.79 0 0 1 2.89 6.97c0 5.43-4.43 9.87-9.83 9.87zm5.4-7.39c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.66.15-.2.3-.76.96-.93 1.16-.17.2-.34.22-.64.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.48-1.76-1.66-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.34.45-.51.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.66-1.6-.9-2.2-.24-.58-.48-.5-.66-.51h-.56c-.2 0-.51.07-.78.37-.27.3-1.02.99-1.02 2.42s1.05 2.8 1.2 3c.15.2 2.06 3.14 5 4.4.7.3 1.24.48 1.67.61.7.22 1.34.19 1.84.12.56-.08 1.75-.72 2-1.41.25-.7.25-1.29.17-1.41-.07-.12-.27-.2-.56-.35z"/>
                </svg>
                Join Free CSCA WhatsApp Group
            </a>
            <p class="wa-sub">গ্রুপে জয়েন করে Course Update, Class Schedule ও Guidance পান।</p>
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
