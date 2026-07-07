<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Invoice</title>
      <style>
         body {
         font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
         /* font-size: 10px; */
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
         /* font-size: 9px; */
         padding-top: 4px;
         }
         .section-title {
         /* font-size: 11px; */
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
         /* font-size: 16px; */
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
         /* font-size: 11px; */
         font-weight: bold;
         color: #444;
         margin-top: 6px;
         }
         .data-table {
         width: 100%;
         border-collapse: collapse;
         /* font-size: 9.5px; */
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
         /* font-size: 9px; */
         text-align: justify;
         margin: 6px 0 8px;
         line-height: 1.4;
         }
         .signature td {
         border-top: 1px solid #333;
         padding-top: 6px;
         /* font-size: 9px; */
         text-align: center;
         }
         .branch-footer {
         margin-top: 4px;
         /* font-size: 8px; */
         color: #666;
         text-align: center;
         }
      </style>
      <style>
         body {
         font-family: 'Helvetica Neue', sans-serif;
         background: #f5f5f5;
         padding: 20px;
         color: #333;
         }
         .invoice {
         max-width: 800px;
         margin: auto;
         background: #fff;
         border-radius: 6px;
         padding: 40px;
         box-shadow: 0 0 10px rgba(0,0,0,0.1);
         }
         .logo {
         width: 100px;
         height: 100%;
         }
         .title {
         float: right;
         text-align: right;
         /* color: #e60000; */
         }
         .title h1 {
         margin: 5px;
         }
         .title h3 {
         margin: 3px;
         }
         .clear {
         clear: both;
         height: 5px;
         width: 100%;
         background-color: red;
         margin-left: -40px;
         padding-right: 80px;
         margin-top: 20px;
         }
         table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 15px;
         }
         table th, table td {
         padding: 10px;
         border-bottom: 1px solid #ddd;
         text-align: left;
         }
         table th {
         background: #ff0000;
         color: #f5f5f5;
         }
         .total {
         text-align: right;
         margin-top: 20px;
         }
         .info-grid {
         display: flex;
         justify-content: space-between;
         flex-wrap: wrap;
         margin-top: 15px;
         }
         .info-box {
         width: 48%;
         /* margin-bottom: 20px; */
         }
         .footer {
         text-align: center;
         margin-top: 40px;
         font-size: 12px;
         color: #888;
         }
         a {
         color: #e60000;
         text-decoration: none;
         }
         /* Print Button Styling */
         #print-button {
         display: block;
         margin: 20px auto;
         padding: 10px 20px;
         background: #e60000;
         color: white;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         font-size: 16px;
         }
         @media print {
         #print-button {
         display: none;
         }
         body, html {
         margin: 0 !important;
         padding: 0 !important;
         width: 100%;
         }
         .invoice {
         /* width: 100%; */
         /* max-width: none; */
         /* padding: 20mm !important; */
         box-shadow: none;
         border-radius: 0;
         page-break-inside: avoid;
         }
         }
         @page {
         size: A4;
         margin: 0;
         }
         span.bold-text {
         font-weight: bold;
         }
         table td:last-child,
         table th:last-child {
         text-align: right; 
         }
         /* Payment method box */
         .payment-box {
         display: inline-block;
         border: 1px solid #e0e0e0;
         background: #fbfbfb;
         padding: 8px 12px;
         border-radius: 6px;
         font-weight: 600;
         margin-top: 6px;
         }
         /* Signature Row Styling start */
         .signature-row {
         display: flex;
         justify-content: space-between;
         margin-top: 30px;
         }
         .signature-box {
         width: 30%;
         text-align: center;
         font-size: 14px;
         }
         /* Signature Row Styling end */
         .footer-blocks {
         display: flex;
         justify-content: space-between;
         /* margin-top: 40px; */
         border-top: 1px solid #ddd;
         padding-top: 20px;
         font-size: 14px;
         color: #555;
         }
         .footer-box {
         width: 32%;
         line-height: 1.5;
         }
         .footer-box a {
         color: #e60000;
         text-decoration: none;
         }
         .marginNull{
         margin: 0;
         }
         .condition {
         text-align: justify;
         text-align-last: left;
         font-size: 12px;
         }
         .main-box {
         width: 800px;
         background: #fff;
         border-radius: 10px;
         box-shadow: 0 4px 10px rgba(0,0,0,0.1);
         display: flex;
         position: relative;
         overflow: hidden;
         }
         /* Divider line */
         .main-box::before {
         content: "";
         position: absolute;
         top: 0;
         bottom: 0;
         left: 50%;
         width: 2px;
         background: #ddd;
         }
         .half {
         flex: 1;
         text-align: center;
         }
         .half h3 {
         margin-bottom: 10px;
         }
         @media (max-width: 600px) {
         .main-box {
         flex-direction: column;
         }
         .main-box::before {
         display: none;
         }
         .half {
         border-bottom: 1px solid #ddd;
         }
         .half:last-child {
         border-bottom: none;
         }
         }
      </style>
   </head>
   <body>
      <button id="print-button" onclick="window.print()">Print Invoice</button>
      <div class="invoice">
         <div style="display: flex; justify-content: space-between;">
            {{-- <!-- <img src="{{ asset('media/custom/Logo-1.png') }}" class="logo" alt="Logo"> --> --}}
            {{-- <img src="{{ asset($branch->logo) }}" class="logo" alt="Logo"> --}}
            <img src="{{ asset($student->branchInfo->logo) }}" class="logo" alt="Logo">
            <div class="title">
               <h3>Complete Declaration Form <br>of Service Received</h3>
               {{-- 
               <div><span class="bold-text">Invoice No:</span> {{$invoice->invoice_no}}</div>
               --}}
               <div><span class="bold-text">Date:</span> {{$invoice->created_at->format('F j, Y')}}</div>
               {{-- 
               <div><span>Print:</span> {{now()->format('F j, Y');}}</div>
               --}}
            </div>
         </div>
         <div class="clear">
         </div>
         {{-- 
         <h3>Invoice to:</h3>
         <p class="marginNull"><span class="bold-text">Student ID: </span>{{$student->st_office_id ?? ''}}</p>
         <p class="marginNull"><span class="bold-text">Student Name: </span>{{$student->first_name. ' '. $student->last_name}}</p>
         <p class="marginNull"><span class="bold-text">Address: </span>{{$student->address}}</p>
         <p class="marginNull"><span class="bold-text">Phone No: </span>{{$student->mobile_no}}</p>
         <p class="marginNull"><span class="bold-text">Email: </span>{{$student->email}}</p>
         --}}
         <div class="">
            <div class="">
               <div class="copy-block">
                  <div class="section-title">Student Information</div>
                  <table class="data-table">
                     <tr>
                        <td width="50%"><strong>Student ID:</strong> {{ $student->st_office_id ?? '—' }}</td>
                        <td width="50%"><strong>Name:</strong> {{ trim($student->first_name . ' ' . $student->last_name) ?: '—' }}</td>
                     </tr>
                     <tr>
                        <td><strong>Passport:</strong> {{ $student->passport ?: '—' }}</td>
                        <td colspan="2"><strong>Address:</strong> {{ $student->address ?: '—' }}</td>
                     </tr>
                     <tr>
                     </tr>
                     <tr>
                     </tr>
                     <tr>
                        <td><strong>Senior Counselor:</strong> {{ optional($student->sncounselor)->name ?? '—' }}</td>
                        <td><strong>Junior Counselor:</strong> {{ optional($student->jncounselor)->name ?? '—' }}</td>
                     </tr>
                     <tr>
                        <td><strong>Applied Program:</strong> {{ $student->program ?? 'bachelor' }}</td>
                        <td>
                            <strong>Scholarship:</strong>
                            @if(isset($scholarship_status) && $scholarship_status === 'yes')
                                Yes
                            @elseif(isset($scholarship_status) && $scholarship_status === 'no')
                                No
                            @else
                                —
                            @endif
                            @if(isset($scholarship_status) && $scholarship_status === 'yes' && !empty($scholarship_details))
                                <div style="margin-top: 8px;">{{ $scholarship_details }}</div>
                            @endif
                        </td>
                     </tr>
                  </table>
                  <div class="section-title">Admitted University</div>
                  <table class="data-table">
                     <tr>
                        <th>University</th>
                        <th>Subject</th>
                        <th>Intake</th>
                        <th>Application No</th>
                     </tr>
                     @forelse($selected_applications as $application)
                         <tr>
                             <td>{{ optional($application->university)->post_title ?? '—' }}</td>
                             <td>{{ optional($application->subject)->name ?? '—' }}</td>
                             <td>{{ $application->intake ?: '—' }}</td>
                             <td>{{ $application->application_no ?: '—' }}</td>
                         </tr>
                     @empty
                         <tr>
                             <td colspan="4" style="text-align: center;">No applications were selected.</td>
                         </tr>
                     @endforelse
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
                        <td width="34%">Guardian's Signature</td>
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
                     @forelse($invoices as $index => $inv)
                         @php
                             $services = implode(', ', \App\Services\ServiceCompletedDocumentService::invoiceServiceLines($inv));
                         @endphp
                         <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $inv->invoice_no ?: '—' }}</td>
                            <td>{{ optional($inv->invoice_date)->format('Y-m-d') ?: optional($inv->created_at)->format('Y-m-d') ?: '—' }}</td>
                            <td>{{ $services ?: '—' }}</td>
                            <td class="num">{{ number_format((float) $inv->grandtotal, 2) }}</td>
                            <td class="num">{{ number_format((float) $inv->discount, 2) }}</td>
                            <td class="num">{{ number_format((float) $inv->paid, 2) }}</td>
                            <td class="num">{{ number_format((float) $inv->due, 2) }}</td>
                         </tr>
                     @empty
                         <tr>
                             <td colspan="8" style="text-align: center;">No invoice history available.</td>
                         </tr>
                     @endforelse
                     <tr class="totals-row">
                        <td colspan="4" class="num">Total</td>
                        <td class="num">{{ number_format((float) ($invoice_summary['grandtotal'] ?? 0), 2) }}</td>
                        <td class="num">{{ number_format((float) ($invoice_summary['discount'] ?? 0), 2) }}</td>
                        <td class="num">{{ number_format((float) ($invoice_summary['paid'] ?? 0), 2) }}</td>
                        <td class="num">{{ number_format((float) ($invoice_summary['due'] ?? 0), 2) }}</td>
                     </tr>
                  </table>
               </div>
            </div>
         </div>
         {{-- <div class="signature-row">
            <div class="signature-box">
               <p>_________________________</p>
               <p>Applicant / Guardian's Signature</p>
            </div>
            <div class="signature-box">
               <p>_________________________</p>
               <p>Cashier / Accountant</p>
            </div>
            <div class="signature-box">
               <p>_________________________</p>
               <p>Counselor Signature</p>
            </div>
         </div> --}}
         <div class="footer-blocks">
            <div class="footer-box">
               <strong>Phone</strong><br>
               {!! str_replace(',', '<br>', $student->branchInfo->phone) !!}
            </div>
            <div class="footer-box">
               <strong>Address</strong><br>
               {{ $student->branchInfo->address}}
            </div>
            <div class="footer-box">
               <strong>Website</strong><br>
               <a href="https://areducation.net">areducation.net</a><br>
               {{$student->branchInfo->email}}
            </div>
         </div>
      </div>
   </body>
</html>