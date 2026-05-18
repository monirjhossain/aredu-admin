<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice</title>
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
      <!-- <img src="{{ asset('media/custom/Logo-1.png') }}" class="logo" alt="Logo"> -->
      {{-- <img src="{{ asset($branch->logo) }}" class="logo" alt="Logo"> --}}
      <img src="{{ asset($student->branchInfo->logo) }}" class="logo" alt="Logo">
      <div class="title">
        <h1>Invoice</h1>
        <div><span class="bold-text">Invoice No:</span> {{$invoice->invoice_no}}</div>
        <div><span class="bold-text">Date:</span> {{$invoice->created_at->format('F j, Y')}}</div>
        <div><span>Print:</span> {{now()->format('F j, Y');}}</div>
      </div>
    </div>

    <div class="clear">
    </div>
    {{-- <div>
        <p class="invoice-text">Invoice</p>
    </div> --}}
    <h3>Invoice to:</h3>
    <p class="marginNull"><span class="bold-text">Student ID: </span>{{$student->st_office_id ?? ''}}</p>
    <p class="marginNull"><span class="bold-text">Student Name: </span>{{$student->first_name. ' '. $student->last_name}}</p>
    <p class="marginNull"><span class="bold-text">Address: </span>{{$student->address}}</p>
    <p class="marginNull"><span class="bold-text">Phone No: </span>{{$student->mobile_no}}</p>
    <p class="marginNull"><span class="bold-text">Email: </span>{{$student->email}}</p>
    <div class="main-box">
    <div class="half">
      <p>Senior Counselor: <b>{{$student->sncounselor->name ?? ''}}</b></p>
    </div>
    <div class="half">
      <p>Junior Counselor: <b>{{$student->jncounselor->name ?? ''}}</b></p>
    </div>
  </div>
    <table>
      <thead>
        <tr>
          <th>SL</th>
          <th>Description</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        @foreach($invoice['products']['product'] as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item }}</td>
                <td style="text-align: right;">{{ number_format($invoice['products']['price'][$index]) }}</td>
            </tr>
        @endforeach

        {{-- @foreach($invoice->products as $item)
        <tr>
          <td>01</td>
          <td>MHP22AM/A</td>
          <td>150,000.00</td>
        </tr> --}}
      </tbody>
    </table>

    <div class="total">
      <p class="marginNull">Subtotal: {{number_format($invoice->subtotal)}}</p>
      {{-- Previously paid amount (computed in controller) --}}
      @if (isset($previousPaid) && $previousPaid > 0)
        <p class="marginNull"><strong>Previously Paid: -{{ number_format($previousPaid) }}</strong></p>
      @endif

      {{-- Current invoice paid amount --}}
      @if ($invoice->paid != 0)
      <p class="marginNull"><strong>{{$invoice->is_readable == 1 ? "" : "(Non-refundable)"}} File Opening Fee Paid: -{{number_format($invoice->paid)}}</strong></p>
      @endif
      @if ($invoice->discount != 0)
      <p class="marginNull">Special discount: -{{number_format($invoice->discount)}}</p>
      @endif
      <p class="marginNull"><strong>Due: {{number_format($invoice->due)}}</strong></p>
      <div class="payment-box"><strong>Payment Method:</strong>{{ $invoice->wallet->name}}</div>
  @if ($invoice->payment_method)
  <div class="payment-box"><strong>Payment Method:</strong> {{$invoice->payment_method}}</div>
  @endif
    </div>

    <div class="info-grid">
      <div class="info-box">
        <h4>Declaration</h4>
        <p class="condition">
          1. The deposited Application Fee is Refundable if AR Education is unable to admit by any means/reason <br><br>
          2. After Completing your admission by AR Education if students are willingly unable to take admission the deposited application fee is non-refundable.<br><br>
          3.Documents as well as deposited application fees are non returnable during the application processing stage as all documents remain at China's head office but the student can receive their document at August/September.<br><br>
          4. Need to pay the rest of the service charges at the time of visa collection.<br><br>
          5.If students want to take back their files after getting the confirmation letter from the university then he/she need to pay the full service charge.<br><br>
          6. If students submit a free file, they have to deposit the required amount in each Step.<br><br>
        </p>
      </div>

      <div class="info-box" >
        <h4>Attach documents</h4>
        <ul>
          @foreach($documents as $doc)
              @if(is_array($invoice['documents']) && in_array($doc['id'], $invoice['documents']))
                  <li>{{ $doc['name'] }}</li>
              {{-- @else
                    <li>No documents are attached</li> --}}
              @endif
          @endforeach
        </ul>
        <div>
          
          {{-- <div style="margin-top: 80px; text-align:right">
              <p>__________________________</p>
              <p>Authorized Signature <br>
                Invoice by <b>{{$user->name}}</b>
              </p>
          </div> --}}

          {{-- <div style="margin-top: 60px; text-align:right">
              <p>File Recieved By <b>{{$student->responsiblePerson->name ?? ''}}</b><br>
                 Phone Number : <b>{{$student->responsiblePerson->mobile_no ?? ''}}</b>
              </p>
          </div> --}}
          
        </div>
      </div>

      <div class="info-box" >
        
      </div>
    </div>
    
<div class="signature-row">
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
</div>
    
<div class="footer-blocks">
  <div class="footer-box">
    <strong>Phone</strong><br>
    <!-- {{ $student->branchInfo->phone}} -->
    {!! str_replace(',', '<br>', $student->branchInfo->phone) !!}
    <!-- +880 1768-820865<br>
    +880 1736-318321<br>
    +880 1811-256545 -->
  </div>

  <div class="footer-box">
    <strong>Address</strong><br>
    {{ $student->branchInfo->address}}
    <!-- Tropical Tower, 10th Floor,<br>
    Bata Signal, Elephant Road -->
  </div>

  <div class="footer-box">
    <strong>Website</strong><br>
    <a href="https://areducation.net">areducation.net</a><br>
    {{$student->branchInfo->email}}
    <!-- areducation16@gmail.com -->
  </div>
</div>

  </div>

</body>
</html>
