@extends('layouts.public-application')

@section('title', $title)

@section('content')
<div class="hero">
    <div class="logo-wrap">
        <img src="{{ $logoUrl }}" alt="AR Education Logo">
    </div>
</div>

<div class="card">
    <div class="card-body text-center" style="padding: 48px 24px;">
        <div class="success-icon">✓</div>
        <h1 style="margin: 0 0 12px; font-size: 1.75rem;">Thank You!</h1>
        <p style="color: #6b7280; line-height: 1.7; margin: 0 0 28px;">
            {{ session('success', 'Your application has been submitted successfully. Our team will contact you soon.') }}
        </p>
        <a href="{{ url('application-form') }}" class="submit-btn" style="display: inline-block; width: auto; text-decoration: none; padding: 12px 28px;">
            Submit Another Application
        </a>
    </div>
</div>

<p class="footer-note">
    <a href="https://areducation.net/" target="_blank" rel="noopener">Back to AR Education</a>
</p>
@endsection
