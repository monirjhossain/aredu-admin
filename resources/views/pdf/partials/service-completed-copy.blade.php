@php
    use App\Services\ServiceCompletedDocumentService;
    $app = $admitted_application;
@endphp

<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 0;">
    <tr>
        <td align="right" valign="top">
            <div class="cert-title">SERVICE COMPLETED</div>
            <div class="cert-subtitle">CERTIFICATE</div>
            <div style="margin-top: 6px;"><strong>Certificate No:</strong> {{ $certificate_no }}</div>
            <div><strong>Date:</strong> {{ $generated_at->format('d M Y') }}</div>
            <div class="copy-label">{{ $copy_label }}</div>
            @if (!empty($office_copy_note))
                <div style="font-size: 8px; color: #888;">{{ $office_copy_note }}</div>
            @endif
        </td>
    </tr>
</table>
<div class="red-bar"></div>

<div class="section-title">Student Information</div>
<table class="data-table">
    <tr>
        <td width="50%"><strong>Student ID:</strong> {{ $office_student_id ?: '—' }}</td>
        <td width="50%"><strong>Name:</strong> {{ $student->full_name }}</td>
    </tr>
    <tr>
        <td><strong>Passport:</strong> {{ $student->passport ?: '—' }}</td>
        <td><strong>Phone:</strong> {{ $student->mobile_no ?: '—' }}</td>
    </tr>
    <tr>
        <td><strong>Email:</strong> {{ $student->email ?: '—' }}</td>
        <td><strong>Office Status:</strong> {{ optional($student->student_status)->name ?? '—' }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Address:</strong> {{ $student_address }}</td>
    </tr>
    <tr>
        <td><strong>Senior Counselor:</strong> {{ optional($student->sncounselor)->name ?? '—' }}</td>
        <td><strong>Assistant Counselor:</strong> {{ optional($student->jncounselor)->name ?? '—' }}</td>
    </tr>
</table>

<div class="section-title">Admitted University</div>
<table class="data-table">
    <tr>
        <th>University</th>
        <th>Subject</th>
        <th>Intake</th>
        <th>Status</th>
        <th>Application No</th>
    </tr>
    <tr>
        <td>{{ optional($app->university)->post_title ?? '—' }}</td>
        <td>{{ optional($app->subject)->name ?? '—' }}</td>
        <td>{{ $display_intake ?? ($app->intake ?: ($student->intake ?: '—')) }}</td>
        <td>{{ optional($app->statusUni)->name ?? '—' }}</td>
        <td>{{ $app->application_no ?: '—' }}</td>
    </tr>
</table>

<div class="section-title">Invoice Payment History</div>
<table class="data-table">
    <tr>
        <th>#</th>
        <th>Invoice</th>
        <th>Date</th>
        <th>Services</th>
        <th class="num">Total</th>
        <th class="num">Paid</th>
        <th class="num">Due</th>
    </tr>
    @forelse ($invoices as $index => $inv)
        @php $services = implode(', ', ServiceCompletedDocumentService::invoiceServiceLines($inv)); @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $inv->invoice_no }}</td>
            <td>{{ $inv->invoice_date ?: optional($inv->created_at)->format('Y-m-d') }}</td>
            <td>{{ $services ?: '—' }}</td>
            <td class="num">{{ number_format((float) $inv->grandtotal, 2) }}</td>
            <td class="num">{{ number_format((float) $inv->paid, 2) }}</td>
            <td class="num">{{ number_format((float) $inv->due, 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" style="text-align: center;">No invoices found.</td>
        </tr>
    @endforelse
    <tr class="totals-row">
        <td colspan="4" class="num">Totals</td>
        <td class="num">{{ number_format($invoice_summary['grandtotal'], 2) }}</td>
        <td class="num">{{ number_format($invoice_summary['paid'], 2) }}</td>
        <td class="num">{{ number_format($invoice_summary['due'], 2) }}</td>
    </tr>
</table>

<div class="declaration">{{ $declaration }}</div>

<table width="100%" cellpadding="0" cellspacing="0" class="signature">
    <tr>
        <td width="33%">Student Signature</td>
        <td width="34%">Counselor Signature</td>
        <td width="33%">Authorized Officer</td>
    </tr>
</table>

@if (!empty($branch))
    <div class="branch-footer">
        {{ $branch->name }}
        @if ($branch->phone) | {{ $branch->phone }} @endif
    </div>
@endif
