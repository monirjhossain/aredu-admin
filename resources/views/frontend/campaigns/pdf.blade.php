<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #111827; }
        .slip { border: 2px solid #fdba74; padding: 22px 18px; text-align: center; }
        .logo { width: 92px; height: auto; margin-bottom: 8px; }
        .brand { margin: 0; font-size: 13px; letter-spacing: 1px; color: #9a3412; font-weight: bold; }
        .campaign { margin: 10px 0 0; font-size: 16px; font-weight: bold; }
        .amount { margin: 14px 0 4px; font-size: 26px; font-weight: bold; color: #c2410c; }
        .label { margin: 0; font-size: 12px; color: #6b7280; }
        .code {
            margin: 16px 0;
            padding: 12px 8px;
            background: #fff7ed;
            border: 1px dashed #fdba74;
            color: #9a3412;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .meta { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .meta td { padding: 7px 4px; border-bottom: 1px solid #ffedd5; font-size: 13px; text-align: left; }
        .meta td:first-child { color: #6b7280; width: 38%; }
        .note { margin: 16px 0 0; font-size: 12px; line-height: 1.5; color: #4b5563; }
    </style>
</head>
<body>
    <div class="slip">
        <img class="logo" src="{{ $logo }}" alt="AR Education">
        <p class="brand">AR EDUCATION</p>
        <p class="campaign">{{ $campaign->name }}</p>
        <p class="amount">{{ number_format($claim->discount_amount) }} BDT discount</p>
        <p class="label">File Opening charge</p>
        <div class="code">{{ $claim->claim_code }}</div>
        <table class="meta">
            <tr>
                <td>Name</td>
                <td>{{ $claim->name }}</td>
            </tr>
            <tr>
                <td>Mobile</td>
                <td>{{ $claim->mobile }}</td>
            </tr>
            <tr>
                <td>College</td>
                <td>{{ $claim->college_name }}</td>
            </tr>
        </table>
        <p class="note">Show this slip at the AR Education office. The discount will be applied on your File Opening charge.</p>
    </div>
</body>
</html>
