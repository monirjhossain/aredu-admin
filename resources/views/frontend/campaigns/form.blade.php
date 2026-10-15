@extends('layouts.public-application')

@section('title', $campaign->name)

@section('content')
<style>
    .page-wrap:has(.camp-form) {
        max-width: 440px;
        padding: 16px 14px 28px;
    }
    .camp-form .hero { margin-bottom: 16px; }
    .camp-form .hero h1 { font-size: clamp(1.35rem, 6vw, 1.75rem); }
    .camp-form .hero p { font-size: clamp(0.9rem, 3.4vw, 0.98rem); }
    .camp-form .wizard-body { padding: 20px 16px 18px; }
    .camp-form .form-group { margin-bottom: 14px; }
    .camp-form .form-control {
        min-height: 48px;
        font-size: 16px;
        border-radius: 12px;
    }
    .camp-form .btn-next {
        min-height: 50px;
        width: 100%;
        margin-top: 6px;
        border-radius: 14px;
        font-size: 1rem;
    }
    .camp-popup {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        background: rgba(17, 24, 39, 0.62);
        padding: 0;
        animation: campFade 0.28s ease both;
    }
    .camp-confetti {
        position: fixed;
        inset: 0;
        z-index: 1100;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }
    .camp-popup__card {
        width: 100%;
        max-height: min(92dvh, 680px);
        overflow: auto;
        background: #fff;
        border-radius: 22px 22px 0 0;
        padding: 22px 18px calc(18px + env(safe-area-inset-bottom));
        text-align: center;
        box-shadow: 0 -12px 40px rgba(17, 24, 39, 0.18);
        animation: campPop 0.62s cubic-bezier(0.16, 1.35, 0.35, 1) both;
    }
    .camp-popup__party {
        font-size: 2.4rem;
        line-height: 1;
        animation: campParty 0.7s 0.12s cubic-bezier(0.16, 1.4, 0.35, 1) both;
    }
    .camp-popup__congrats {
        margin: 8px 0 0;
        font-size: 0.95rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #ea580c;
    }
    .camp-popup__amount {
        margin: 8px 0 4px;
        font-size: clamp(1.7rem, 8vw, 2.2rem);
        font-weight: 800;
        letter-spacing: -0.03em;
        color: #c2410c;
        line-height: 1.15;
        animation: campAmount 0.55s 0.18s cubic-bezier(0.16, 1.4, 0.35, 1) both;
    }
    .camp-popup__code {
        margin: 14px 0;
        padding: 14px 12px;
        border-radius: 14px;
        background: #fff7ed;
        border: 1px dashed #fdba74;
        color: #9a3412;
        font-size: clamp(1.05rem, 4.8vw, 1.35rem);
        font-weight: 800;
        letter-spacing: 0.03em;
        word-break: break-word;
        line-height: 1.35;
    }
    .camp-popup__meta {
        margin: 0 0 8px;
        color: #4b5563;
        font-size: 0.92rem;
        line-height: 1.5;
        word-break: break-word;
    }
    .camp-popup__note {
        margin: 0 0 16px;
        color: #6b7280;
        font-size: 0.86rem;
        line-height: 1.55;
    }
    .camp-popup__pdf,
    .camp-popup__close {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 48px;
        border-radius: 14px;
        font-weight: 700;
        text-decoration: none;
    }
    .camp-popup__pdf {
        margin: 0 0 10px;
        background: #ea580c;
        color: #fff;
    }
    .camp-popup__close {
        background: #111827;
        color: #fff;
    }
    @media (min-width: 640px) {
        .camp-popup {
            align-items: center;
            padding: 24px;
        }
        .camp-popup__card {
            max-width: 420px;
            border-radius: 22px;
            padding: 28px 24px 22px;
        }
    }
    @keyframes campFade {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes campPop {
        0% { opacity: 0; transform: translateY(48px) scale(0.86); }
        70% { opacity: 1; transform: translateY(-8px) scale(1.03); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes campParty {
        0% { opacity: 0; transform: scale(0.2) rotate(-18deg); }
        70% { transform: scale(1.18) rotate(8deg); }
        100% { opacity: 1; transform: scale(1) rotate(0); }
    }
    @keyframes campAmount {
        0% { opacity: 0; transform: scale(0.7); }
        100% { opacity: 1; transform: scale(1); }
    }
    @media (prefers-reduced-motion: reduce) {
        .camp-popup,
        .camp-popup__card,
        .camp-popup__party,
        .camp-popup__amount { animation: none; }
        .camp-confetti { display: none; }
    }
</style>

<div class="camp-form">
    <div class="hero">
        <div class="logo-wrap">
            <img src="{{ asset('media/custom/logo.png') }}" alt="AR Education">
        </div>
        <h1>{{ $campaign->name }}</h1>
        <p>Fill in your details. Your File Opening discount is chosen at random.</p>
        <div class="branch-badge">Random discount</div>
    </div>

    <div class="wizard-card">
        <div class="wizard-body">
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!$campaign->isActive() || $spots < 1)
                <h2 class="step-heading">New claims are closed</h2>
                <p class="step-subtitle">{{ $spots < 1 ? 'All discount spots on this QR have been claimed.' : 'This QR is not accepting new students right now.' }} If you already claimed, enter the same mobile number to see your code.</p>
            @else
                <h2 class="step-heading">Claim your discount</h2>
                <p class="step-subtitle">{{ $spots }} spots left. The amount is picked at random.</p>
            @endif
                <form method="POST" action="{{ route('campaigns.public.store', $campaign->token) }}">
                    @csrf
                    <div class="form-group">
                        <label class="required" for="name">Name</label>
                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name', optional($claim)->name) }}" required placeholder="Your full name" autocomplete="name">
                    </div>
                    <div class="form-group">
                        <label class="required" for="mobile">Mobile number</label>
                        <input id="mobile" name="mobile" type="tel" class="form-control" value="{{ old('mobile', optional($claim)->mobile) }}" required placeholder="01XXXXXXXXX" autocomplete="tel" inputmode="tel">
                    </div>
                    <div class="form-group">
                        <label class="required" for="college_name">College Name</label>
                        <input id="college_name" name="college_name" type="text" class="form-control" value="{{ old('college_name', optional($claim)->college_name) }}" required placeholder="Your college">
                    </div>
                    <button type="submit" class="btn btn-next">Get my discount</button>
                </form>
        </div>
    </div>
</div>

@if(!empty($claim))
<canvas class="camp-confetti" id="campConfetti" aria-hidden="true"></canvas>
<div class="camp-popup" role="dialog" aria-modal="true" aria-labelledby="campPopupTitle">
    <div class="camp-popup__card">
        <div class="camp-popup__party" aria-hidden="true">🎉</div>
        <p class="camp-popup__congrats">Congratulations</p>
        <div class="branch-badge">{{ $campaign->name }}</div>
        <div class="camp-popup__amount" id="campPopupTitle">You got {{ number_format($claim->discount_amount) }} BDT discount</div>
        <div class="camp-popup__code">{{ $claim->claim_code }}</div>
        <p class="camp-popup__meta">{{ $claim->name }} · {{ $claim->college_name }}</p>
        <p class="camp-popup__note">Show this code at the office. It will be applied on your File Opening charge. Take a screenshot before you close this.</p>
        <a class="camp-popup__pdf" href="{{ route('campaigns.public.pdf', [$campaign->token, $claim->claim_code]) }}">Download PDF</a>
        <a class="camp-popup__close" href="{{ route('campaigns.public.show', $campaign->token) }}">Done</a>
    </div>
</div>
@endif
@endsection

@if(!empty($claim))
@push('scripts')
<script>
(function () {
    var canvas = document.getElementById('campConfetti');
    if (!canvas || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    document.body.style.overflow = 'hidden';
    var ctx = canvas.getContext('2d');
    var colors = ['#f97316', '#fb923c', '#fbbf24', '#fde68a', '#ffffff', '#22c55e', '#fb7185'];
    var pieces = [];
    function resize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);
    for (var i = 0; i < 110; i++) {
        pieces.push({
            x: Math.random() * canvas.width,
            y: -20 - Math.random() * canvas.height * 0.85,
            w: 6 + Math.random() * 7,
            h: 8 + Math.random() * 12,
            color: colors[i % colors.length],
            vy: 2.4 + Math.random() * 3.6,
            vx: -1.6 + Math.random() * 3.2,
            rot: Math.random() * Math.PI,
            vr: -0.18 + Math.random() * 0.36,
            sway: Math.random() * Math.PI * 2
        });
    }
    var start = performance.now();
    function frame(now) {
        var elapsed = now - start;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        pieces.forEach(function (p) {
            p.y += p.vy;
            p.x += p.vx + Math.sin((p.sway + elapsed) / 280) * 0.7;
            p.rot += p.vr;
            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate(p.rot);
            ctx.fillStyle = p.color;
            ctx.globalAlpha = elapsed > 3200 ? Math.max(0, 1 - (elapsed - 3200) / 900) : 1;
            ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
            ctx.restore();
        });
        if (elapsed < 4200) requestAnimationFrame(frame);
        else canvas.remove();
    }
    requestAnimationFrame(frame);
})();
</script>
@endpush
@endif
