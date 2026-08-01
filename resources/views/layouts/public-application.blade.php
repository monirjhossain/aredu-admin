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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f97316;
            --primary-dark: #ea580c;
            --primary-light: #ffedd5;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #e5e7eb;
            --bg: #fff7ed;
            --white: #ffffff;
            --shadow: 0 20px 50px rgba(249, 115, 22, 0.12);
            --radius: 16px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--text);
            background: linear-gradient(160deg, #fff7ed 0%, #ffffff 45%, #fff1e6 100%);
            min-height: 100vh;
        }

        .page-wrap {
            max-width: 760px;
            margin: 0 auto;
            padding: 24px 16px 48px;
        }

        .hero {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 96px;
            height: 96px;
            border-radius: 24px;
            background: var(--white);
            box-shadow: var(--shadow);
            margin-bottom: 18px;
            padding: 12px;
        }

        .logo-wrap img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .hero h1 {
            margin: 0 0 8px;
            font-size: clamp(1.5rem, 4vw, 2rem);
            font-weight: 700;
            color: var(--text);
        }

        .hero p {
            margin: 0;
            color: var(--muted);
            font-size: 0.98rem;
            line-height: 1.6;
        }

        .card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(249, 115, 22, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: 18px 24px;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .card-body { padding: 24px; }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.92rem;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .form-grid .full-width {
            grid-column: 1 / -1;
        }

        @media (min-width: 640px) {
            .form-grid.two-col {
                grid-template-columns: 1fr 1fr;
            }
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text);
        }

        .required::after {
            content: ' *';
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 12px;
            font: inherit;
            font-size: 0.95rem;
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.12);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .invalid-feedback {
            display: block;
            margin-top: 6px;
            color: #dc2626;
            font-size: 0.82rem;
        }

        .is-invalid {
            border-color: #fca5a5 !important;
            background: #fffafa !important;
        }

        .submit-btn {
            width: 100%;
            margin-top: 8px;
            padding: 14px 20px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            font: inherit;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 10px 24px rgba(249, 115, 22, 0.28);
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(249, 115, 22, 0.34);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .footer-note {
            text-align: center;
            margin-top: 24px;
            color: var(--muted);
            font-size: 0.85rem;
        }

        .footer-note a {
            color: var(--primary-dark);
            text-decoration: none;
            font-weight: 600;
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
