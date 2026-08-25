<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Application') — AR Education</title>
    <link rel="shortcut icon" href="{{ asset('media/custom/fabicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --primary-light: #ffedd5;
            --primary-glow: rgba(249, 115, 22, 0.18);
            --text: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
            --white: #ffffff;
            --shadow: 0 24px 60px rgba(17, 24, 39, 0.08);
            --radius: 20px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 28%),
                radial-gradient(circle at bottom left, rgba(251, 146, 60, 0.08), transparent 24%),
                linear-gradient(180deg, #fff8f1 0%, #ffffff 40%, #fff4ea 100%);
            min-height: 100vh;
        }

        .page-wrap {
            max-width: 760px;
            margin: 0 auto;
            padding: 20px 16px 40px;
        }

        .csca-landing { margin-bottom: 22px; text-align: left; }
        .csca-kicker {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; border: 1px solid rgba(249,115,22,.18);
            color: #c2410c; font-size: .78rem; font-weight: 800;
            padding: 7px 12px; border-radius: 999px; margin-bottom: 12px;
        }
        .csca-lead {
            font-size: 1.02rem; line-height: 1.8; color: #374151; margin: 0 0 16px;
        }
        .csca-courses {
            display: grid; gap: 10px; margin: 0 0 18px;
        }
        .csca-course {
            background: #fff; border: 1px solid rgba(249,115,22,.12);
            border-radius: 14px; padding: 12px 14px;
            font-weight: 700; color: #9a3412; display: flex; gap: 10px; align-items: flex-start;
            box-shadow: 0 10px 24px rgba(17,24,39,.04);
        }
        .csca-course span { color: #16a34a; font-size: 1.05rem; line-height: 1.2; }
        .csca-note {
            background: linear-gradient(180deg, #fff7ed 0%, #fff 100%);
            border: 1px solid rgba(249,115,22,.14);
            border-radius: 16px; padding: 16px 16px 4px; margin-bottom: 16px;
        }
        .csca-note p { margin: 0 0 12px; color: #4b5563; font-size: .94rem; line-height: 1.75; }
        .csca-stats {
            display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin: 0 0 16px;
        }
        .csca-stat {
            background: #111827; color: #fff; border-radius: 16px; padding: 16px 14px; text-align: center;
        }
        .csca-stat strong { display: block; font-size: 1.45rem; font-weight: 800; color: #fb923c; }
        .csca-stat em { font-style: normal; font-size: .78rem; color: #d1d5db; }
        .csca-why h3 {
            margin: 0 0 10px; font-size: 1.08rem; font-weight: 800;
        }
        .csca-unis { display: flex; flex-wrap: wrap; gap: 8px; margin: 0 0 18px; }
        .csca-uni {
            background: #fff; border: 1px solid #e5e7eb; border-radius: 999px;
            padding: 7px 12px; font-size: .78rem; font-weight: 700; color: #374151;
        }
        .wa-btn {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 14px 18px; border-radius: 14px; text-decoration: none;
            background: #25D366; color: #fff; font-weight: 800; font-size: .98rem;
            box-shadow: 0 12px 28px rgba(37,211,102,.28); margin: 0 0 10px;
        }
        .wa-btn:hover { filter: brightness(1.05); }
        .wa-sub { text-align: center; color: #6b7280; font-size: .82rem; margin: 0 0 20px; }
        .wa-float {
            position: fixed; right: 16px; bottom: 16px; z-index: 80;
            width: 58px; height: 58px; border-radius: 50%;
            background: #25D366; color: #fff; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 12px 30px rgba(37,211,102,.4); text-decoration: none;
        }
        @media (max-width: 520px) {
            .csca-stats { grid-template-columns: 1fr; }
        }

        .hero {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 88px;
            height: 88px;
            border-radius: 22px;
            background: var(--white);
            box-shadow: var(--shadow);
            margin-bottom: 16px;
            padding: 10px;
            border: 1px solid rgba(249, 115, 22, 0.08);
        }

        .logo-wrap img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .hero h1 {
            margin: 0 0 8px;
            font-size: clamp(1.45rem, 4vw, 1.9rem);
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .hero p {
            margin: 0;
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.65;
            max-width: 520px;
            margin-inline: auto;
        }

        .branch-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            padding: 8px 14px;
            border-radius: 999px;
            background: var(--white);
            border: 1px solid rgba(249, 115, 22, 0.15);
            color: var(--primary-dark);
            font-size: 0.82rem;
            font-weight: 700;
            box-shadow: 0 8px 24px rgba(249, 115, 22, 0.08);
        }

        .branch-badge::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary);
        }

        .wizard-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(249, 115, 22, 0.08);
            overflow: hidden;
        }

        .wizard-progress {
            padding: 22px 20px 0;
            background: linear-gradient(180deg, #fffaf5 0%, #ffffff 100%);
            border-bottom: 1px solid rgba(249, 115, 22, 0.08);
        }

        .progress-track {
            height: 6px;
            background: #f3f4f6;
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 18px;
        }

        .progress-fill {
            height: 100%;
            width: 25%;
            background: linear-gradient(90deg, var(--primary) 0%, #fb923c 100%);
            border-radius: 999px;
            transition: width 0.35s ease;
        }

        .steps-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            padding-bottom: 18px;
        }

        .step-item {
            text-align: center;
            min-width: 0;
        }

        .step-circle {
            width: 38px;
            height: 38px;
            margin: 0 auto 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            font-weight: 800;
            background: #f3f4f6;
            color: #9ca3af;
            border: 2px solid transparent;
            transition: all 0.25s ease;
        }

        .step-item.active .step-circle {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            box-shadow: 0 10px 24px var(--primary-glow);
        }

        .step-item.completed .step-circle {
            background: #ecfdf5;
            color: #059669;
            border-color: #a7f3d0;
        }

        .step-label {
            display: block;
            font-size: 0.68rem;
            font-weight: 700;
            color: #9ca3af;
            line-height: 1.3;
        }

        .step-item.active .step-label { color: var(--primary-dark); }
        .step-item.completed .step-label { color: #059669; }

        @media (max-width: 520px) {
            .step-label { font-size: 0.62rem; }
            .step-circle { width: 34px; height: 34px; }
        }

        .wizard-body { padding: 24px 20px 20px; }

        .step-panel {
            display: none;
            animation: fadeSlide 0.35s ease;
        }

        .step-panel.active { display: block; }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .step-heading {
            margin: 0 0 6px;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .step-subtitle {
            margin: 0 0 22px;
            color: var(--muted);
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .field-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        @media (min-width: 600px) {
            .field-grid.two-col { grid-template-columns: 1fr 1fr; }
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--text);
        }

        .required::after {
            content: ' *';
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 13px 15px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            font: inherit;
            font-size: 0.95rem;
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px var(--primary-glow);
        }

        textarea.form-control {
            min-height: 130px;
            resize: vertical;
        }

        .field-error {
            display: none;
            margin-top: 6px;
            color: #dc2626;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .form-control.is-invalid,
        .form-control.server-invalid {
            border-color: #fca5a5 !important;
            background: #fffafa !important;
        }

        .form-group.has-error .field-error { display: block; }

        .invalid-feedback {
            display: block;
            margin-top: 6px;
            color: #dc2626;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .review-box {
            background: #fffaf5;
            border: 1px solid rgba(249, 115, 22, 0.12);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 18px;
        }

        .review-box h3 {
            margin: 0 0 14px;
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--primary-dark);
        }

        .review-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px dashed rgba(249, 115, 22, 0.15);
            font-size: 0.88rem;
        }

        .review-row:last-child { border-bottom: none; padding-bottom: 0; }

        .review-row span:first-child {
            color: var(--muted);
            font-weight: 600;
            flex-shrink: 0;
        }

        .review-row span:last-child {
            text-align: right;
            font-weight: 700;
            word-break: break-word;
        }

        .wizard-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
        }

        .btn {
            border: none;
            border-radius: 14px;
            font: inherit;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
            padding: 14px 18px;
        }

        .btn-back {
            background: #f3f4f6;
            color: #374151;
            min-width: 110px;
        }

        .btn-next,
        .submit-btn {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            box-shadow: 0 12px 28px rgba(249, 115, 22, 0.28);
        }

        .btn:hover { transform: translateY(-1px); }
        .btn:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

        .alert {
            padding: 12px 16px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-size: 0.9rem;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert ul {
            margin: 8px 0 0;
            padding-left: 18px;
        }

        .footer-note {
            text-align: center;
            margin-top: 22px;
            color: var(--muted);
            font-size: 0.84rem;
        }

        .footer-note a {
            color: var(--primary-dark);
            text-decoration: none;
            font-weight: 700;
        }

        .success-icon {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: #ecfdf5;
            color: #059669;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            margin-bottom: 20px;
        }

        .text-center { text-align: center; }
        .card { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow); }
        .card-body { padding: 24px; }

        /* Multi-select (University & Subject) */
        .ms-wrap { position: relative; }
        .ms-box {
            border: 1.5px solid var(--border);
            border-radius: 14px;
            background: #fafafa;
            padding: 10px 12px;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .ms-box:focus-within {
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px var(--primary-glow);
        }
        .ms-box.is-invalid { border-color: #fca5a5; background: #fffafa; }
        .ms-chips { display: flex; flex-wrap: wrap; gap: 8px; min-height: 0; margin-bottom: 8px; }
        .ms-chips:empty { display: none; margin-bottom: 0; }
        .ms-chip {
            display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;
            border-radius: 999px; background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
            color: #9a3412; font-size: 0.82rem; font-weight: 700; line-height: 1.2;
        }
        .ms-chip button {
            border: none; background: rgba(154, 52, 18, 0.12); color: #9a3412;
            width: 18px; height: 18px; border-radius: 50%; cursor: pointer;
            font-size: 0.9rem; line-height: 1; padding: 0;
        }
        .ms-search {
            width: 100%; border: none; background: transparent; font: inherit;
            font-size: 0.92rem; padding: 4px 2px; outline: none; color: var(--text);
        }
        .ms-search::placeholder { color: #9ca3af; }
        .ms-dropdown {
            display: none; position: absolute; left: 0; right: 0; top: calc(100% + 6px);
            max-height: 220px; overflow-y: auto; background: #fff; border: 1px solid var(--border);
            border-radius: 14px; box-shadow: 0 18px 40px rgba(17, 24, 39, 0.12); z-index: 50;
        }
        .ms-dropdown.open { display: block; }
        .ms-option {
            padding: 11px 14px; cursor: pointer; font-size: 0.9rem; font-weight: 600;
            color: #374151; border-bottom: 1px solid #f3f4f6;
        }
        .ms-option:last-child { border-bottom: none; }
        .ms-option:hover, .ms-option.focused { background: #fff7ed; color: #c2410c; }
        .ms-empty { padding: 14px; text-align: center; color: #9ca3af; font-size: 0.86rem; }
        .ms-native { display: none !important; }
        .ms-hint { margin-top: 6px; font-size: 0.78rem; color: #9ca3af; }
        .ms-label-row {
            display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 7px;
        }
        .ms-label-row label { margin-bottom: 0 !important; }
        .ms-count {
            font-size: 0.72rem; font-weight: 700; color: var(--primary-dark);
            background: var(--primary-light); padding: 3px 8px; border-radius: 999px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="page-wrap">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>
