@php
    use App\Services\ServiceCompletedDocumentService;
    $app = $admitted_application;
@endphp

{{-- <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 0;">
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
<div class="red-bar"></div> --}}
<h1>Complete Declaration Form of Service Received</h1>
<div class="section-title">Student Information</div>
<table class="data-table">
    <tr>
        <td width="50%"><strong>Student ID:</strong> {{ $office_student_id ?: '—' }}</td>
        <td width="50%"><strong>Name:</strong> {{ $student->full_name }}</td>
    </tr>
    <tr>
        <td><strong>Passport:</strong> {{ $student->passport ?: '—' }}</td>
        <td colspan="2"><strong>Address:</strong> {{ $student_address }}</td>
        {{-- <td><strong>Phone:</strong> {{ $student->mobile_no ?: '—' }}</td> --}}
    </tr>
    <tr>
        {{-- <td><strong>Email:</strong> {{ $student->email ?: '—' }}</td> --}}
        {{-- <td><strong>Office Status:</strong> {{ optional($student->student_status)->name ?? '—' }}</td> --}}
    </tr>
    <tr>
    </tr>
    <tr>
        <td><strong>Senior Counselor:</strong> {{ optional($student->sncounselor)->name ?? '—' }}</td>
        <td><strong>Junior Counselor:</strong> {{ optional($student->jncounselor)->name ?? '—' }}</td>
    </tr>
    <tr>
        <td><strong>Applied Program:</strong> {{ 'bachelor' }}</td>
        <td><strong>Scholarship:</strong> 
            Yes<input type="radio" name="scholarship"> 
            No<input name="scholarship" type="radio">
            <input  name="scholarship" type="textbox">
        </td>
    </tr>
</table>

<div class="section-title">Application List</div>
<table class="data-table">
    <tr>
        <th>Check</th>
        <th>University</th>
        <th>Subject</th>
        <th>Intake</th>
        <th>Status</th>
        <th>Application No</th>
    </tr>
    <tr>
        {{-- <td>
            <input type="radio" name="application_no">
        </td>
        <td>{{ optional($app->university)->post_title ?? '—' }}</td>
        <td>{{ optional($app->subject)->name ?? '—' }}</td>
        <td>{{ $display_intake ?? ($app->intake ?: ($student->intake ?: '—')) }}</td>
        <td>{{ optional($app->statusUni)->name ?? '—' }}</td>
        <td>{{ $app->application_no ?: '—' }}</td> --}}
        {{-- @if($student->universities_app_status->count())
    @foreach($student->universities_app_status as $application)
        <tr>
            <td>{{ $application->university->post_title ?? 'N/A' }}</td>
            <td>{{ $application->subject->name ?? 'N/A' }}</td>
            <td>{{ $application->intake }}</td>
            <td>{{ $application->status_uni->name ?? 'N/A' }}</td>
        </tr>
    @endforeach
@endif --}}
        {{-- @foreach ($student[universities_app_status] as $item)
            <td>
                <input type="radio" name="application_no">
            </td>
            <td>{{ optional($app->university)->post_title ?? '—' }}</td>
            <td>{{ optional($app->subject)->name ?? '—' }}</td>
            <td>{{ $display_intake ?? ($app->intake ?: ($student->intake ?: '—')) }}</td>
            <td>{{ optional($app->statusUni)->name ?? '—' }}</td>
            <td>{{ $app->application_no ?: '—' }}</td>
        @endforeach --}}
    </tr>
</table>

<div>

<br>
I, <b>{{ $student->full_name }}</b>, hereby declare that I have received complete assistance from <b>AR Education Consultancy</b> in relation to my university application, admission process, and visa processing. Their service has been helpful, professional, and satisfactory. I am fully satisfied with the service provided, including the service fee charged, and I have no complaints regarding their support.
<br><br>
I fully understand that upon my arrival at the university, I will be personally responsible for the following expenses, and that AR Education Consultancy will not bear or be liable for any of these costs:
<br><br>
<ul>
    <li>Tuition Fee (if applicable)</li>
    <li>Hostel/Accommodation Fee (if applicable)</li>
    <li>Registration Fee</li>
    <li>Health Insurance Fee</li>
    <li>Medical Check-up Fee</li>
    <li>Residence Permit Fee</li>
    <li>Any other personal expenses</li>
</ul>
I also confirm that I have received from AR Education Consultancy all documents I had submitted, as well as the University Admission Notice, Visa Letter, and Visa.
</div>

<br><br><br>
<table width="100%" cellpadding="0" cellspacing="0" class="signature">
    <tr>
        <td width="33%">Student Signature</td>
        <td width="34%">Counselor Signature</td>
        <td width="33%">Authorized Officer</td>
    </tr>
</table>

<br><br>


<div class="section-title">Payment History</div>
<table class="data-table">
    <tr>
        <th>#</th>
        <th>Invoice</th>
        <th>Date</th>
        <th>Description</th>
        <th class="num">Amount</th>
        <th class="num">Discount</th>
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
            <td class="num">{{ number_format((float) $inv->discount, 2) }}</td>
            <td class="num">{{ number_format((float) $inv->paid, 2) }}</td>
            <td class="num">{{ number_format((float) $inv->due, 2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" style="text-align: center;">No invoices found.</td>
        </tr>
    @endforelse
    <tr class="totals-row">
        <td colspan="4" class="num">Total</td>
        <td class="num">{{ number_format($invoice_summary['grandtotal'], 2) }}</td>
        <td class="num">{{ number_format($invoice_summary['discount'], 2) }}</td>
        <td class="num">{{ number_format($invoice_summary['paid'], 2) }}</td>
        {{-- <td class="num">{{ number_format($invoice_summary['due'], 2) }}</td> --}}
        <td class="num">0.00</td>
    </tr>
</table>

{{-- <div class="declaration">{{ $declaration }}</div> --}}



{{-- @if (!empty($branch))
    <div class="branch-footer">
        {{ $branch->name }}
        @if ($branch->phone) | {{ $branch->phone }} @endif
    </div>
@endif --}}
