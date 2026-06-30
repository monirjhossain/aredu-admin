<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Completed</title>
    <style>
        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.35;
        }
        .page-wrap {
            page-break-inside: avoid;
        }
        .copy-block {
            width: 100%;
        }
        .cut-line {
            border-top: 2px dashed #999;
            margin: 10px 0;
            text-align: center;
            color: #888;
            font-size: 9px;
            padding-top: 4px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            margin: 0 0 5px;
            color: #c00;
        }
        .red-bar {
            height: 4px;
            background: #c00;
            margin: 8px 0 10px;
        }
        .cert-title {
            font-size: 16px;
            font-weight: bold;
            color: #c00;
            margin: 0;
            line-height: 1.2;
        }
        .cert-subtitle {
            font-size: 12px;
            font-weight: bold;
            margin: 2px 0 0;
        }
        .copy-label {
            font-size: 11px;
            font-weight: bold;
            color: #444;
            margin-top: 6px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
            margin-bottom: 8px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 5px 6px;
            text-align: left;
        }
        .data-table th {
            background: #c00;
            color: #fff;
            border-color: #c00;
            font-weight: bold;
        }
        .data-table .num {
            text-align: right;
        }
        .totals-row {
            background: #f5f5f5;
            font-weight: bold;
        }
        .declaration {
            font-size: 9px;
            text-align: justify;
            margin: 6px 0 8px;
            line-height: 1.4;
        }
        .signature td {
            border-top: 1px solid #333;
            padding-top: 18px;
            font-size: 9px;
            text-align: center;
        }
        .branch-footer {
            margin-top: 4px;
            font-size: 8px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="page-wrap">
        <div class="copy-block">
            @include('pdf.partials.service-completed-copy', ['copy_label' => 'STUDENT COPY'])
        </div>

        <div class="cut-line">— — — cut here — — —</div>

        <div class="copy-block">
            @include('pdf.partials.service-completed-copy', [
                'copy_label' => 'OFFICE COPY',
                'office_copy_note' => 'Office Copy — For internal record only',
            ])
        </div>
    </div>
</body>
</html>
