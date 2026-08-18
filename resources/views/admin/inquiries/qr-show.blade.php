<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #fff7ed;
            color: #1f2937;
        }
        .wrap {
            max-width: 720px;
            margin: 0 auto;
            padding: 32px 20px;
        }
        .card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 16px 40px rgba(249, 115, 22, 0.15);
            padding: 36px 28px;
            text-align: center;
        }
        .logo {
            max-height: 72px;
            margin-bottom: 18px;
        }
        h1 {
            margin: 0 0 8px;
            font-size: 1.6rem;
        }
        .subtitle {
            color: #6b7280;
            margin-bottom: 24px;
        }
        .qr {
            width: 320px;
            max-width: 100%;
            margin: 0 auto 20px;
            display: block;
        }
        .url {
            word-break: break-all;
            color: #ea580c;
            font-size: 0.95rem;
            margin-bottom: 24px;
        }
        .hint {
            color: #6b7280;
            font-size: 0.92rem;
            line-height: 1.6;
        }
        .actions {
            margin-top: 24px;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin: 0 6px;
        }
        .btn-primary {
            background: #f97316;
            color: #fff;
        }
        .btn-light {
            background: #f3f4f6;
            color: #111827;
        }
        @media print {
            body { background: #fff; }
            .actions { display: none; }
            .card { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <img src="{{ $logoUrl }}" alt="Logo" class="logo">
            <h1>{{ $branch->name }}</h1>
            <p class="subtitle">{{ $subtitle ?? 'Student Application Form — Scan QR Code' }}</p>
            <img src="{{ $qrImageUrl }}" alt="Form QR Code" class="qr">
            <div class="url">{{ $formUrl }}</div>
            <p class="hint">
                {{ $hint ?? 'Scan this QR code to open the branch application form.' }}<br>
                Submitted applications will appear in the Inquiry List for this branch.
            </p>
            <div class="actions">
                <a href="javascript:window.print()" class="btn btn-primary">Print QR</a>
                <a href="{{ $backUrl ?? route('application-qr-codes') }}" class="btn btn-light">All QR Codes</a>
            </div>
        </div>
    </div>
</body>
</html>
