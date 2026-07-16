@extends('layouts.default')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{$title}}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    {{-- <li class="breadcrumb-item"><a href="{{ route($url.'.index') }}">{{$type}} list</a></li> --}}
                    <li class="breadcrumb-item active">{{$title}}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="">{{$title}}</h3>
                            @if ($student)
                            <a href="{{ route($url.'.index') }}?student={{ $student->id }}" class="btn btn-primary">Go Back to {{$type}} List</a>
                            @else
                            <a href="{{ route($url.'.index') }}" class="btn btn-primary">Go Back to {{$type}} List</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-12">
                                {{-- <form action="{{ route($url.'.store', [$student->id]) }}" method="POST" enctype="multipart/form-data"> --}}
                                <form action="{{ route($url.'.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    {{-- @method('PUT') --}}
                                        
                                    
                                    <div class="card-body">
                                        @include('includes.errors')
                                        <div class="row">                                
                                            {{-- <div class="col-lg-6 col-md-6 col-sm-12" >
                                                <span><b>Full Name</b> {{ $student->full_name }}</span><br>
                                                <span><b>Email:</b> {{ $student->email }}</span><br>
                                                <span><b>mobile_no:</b> {{ $student->mobile_no }}</span><br>
                                                <span><b>address:</b> {{ $student->address }}</span><br>
                                                <span><b>Email:</b> {{ $student->email }}</span><br>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="invoice_date">Invoice Date</label>
                                                    <input type="date" id="datePicker" name="invoice_date" value="{{ $student->address }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div> --}}

                                            <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                                <div class="card p-3 shadow-sm border-0">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4 mb-2">
                                                            <div class="form-group mb-0">
                                                                <label for="currency" class="font-weight-bold mb-1">Currency</label>
                                                                <select id="currency" name="currency" class="form-control" disabled>
                                                                    @foreach($currencies as $item)
                                                                        <option value="{{$item->id}}">{{$item->code}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="hidden" name="currency" value="1">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <div class="form-group mb-0">
                                                                <label for="payment_method" class="font-weight-bold mb-1">Payment Method</label>
                                                                <select id="payment_method" name="wallet_id" class="form-control">
                                                                    <option value="" {{ old('wallet_id') ? '' : 'selected' }}>Select payment method (optional)</option>
                                                                    @foreach($wallets as $item)
                                                                        <option value="{{$item->id}}" {{ (string) old('wallet_id') === (string) $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-2 d-flex align-items-center">
                                                            <div class="form-group mb-0">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="refundable" name="is_refundable">
                                                                    <label class="custom-control-label" for="refundable">Refundable?</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            <br>
                                            @if($student)
                                            <input type="hidden" name="student_id" value="{{$student->id??''}}">
                                            @else
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="form-group">
                                                    <label for="first_name">Select Student (Optional)</label>
                                                    <select name="student_id" id="StudentList" class="form-control">
                                                        
                                                        <option value="">None</option>
                                                        @foreach ($students as $key => $item)
                                                            <option data-first_name="{{$item->first_name}}" data-last_name="{{$item->last_name}}" data-email="{{$item->email}}" data-address="{{$item->address}}" data-mobile_no="{{$item->mobile_no}}" value="{{$item->id}}">{{$item->id . '. ' . $item->full_name . ($item->passport?' - ' . $item->passport : '')}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="first_name">First Name</label>
                                                    <input id="first_name" type="text" name="first_name" value="{{ $student->first_name??'' }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="last_name">Last Name</label>
                                                    <input id="last_name" type="text" name="last_name" value="{{ $student->last_name??'' }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input id="email" type="text" name="email" value="{{ $student->email??'' }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="mobile_no">Phone No</label>
                                                    <input id="mobile_no" type="text" name="mobile_no" value="{{ $student->mobile_no??'' }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <input id="address" type="text" name="address" value="{{ $student->address??'' }}" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="invoice_date">Invoice Date</label>
                                                    <input type="date" id="datePicker" name="invoice_date" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            {{-- <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="note">Note</label>
                                                    <textarea name="note" id=""  rows="1" class="form-control">{{ $student->note}}</textarea>
                                                </div>
                                            </div> --}}
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <h4>Fees</h4>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 mb-2">
                                                <div class="form-row align-items-end">
                                                    <div class="col-md-8 col-sm-10">
                                                        <div class="form-group mb-0">
                                                            <label for="feeProductSelect">Select Fee</label>
                                                            <select id="feeProductSelect" class="form-control">
                                                                <option value="">Select a fee</option>
                                                                @foreach ($products as $item)
                                                                    <option value="{{ $item->id }}" data-name="{{ $item->name }}">{{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-2">
                                                        <button type="button" id="addFeeBtn" class="btn btn-success btn-block" title="Add fee">
                                                            <i class="fas fa-plus"></i> Add
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table" id="feesTable">
                                                        <tbody id="feesRows"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="subtotal">Sub Total</label>
                                                    <input type="text" name="subtotal" class="form-control" placeholder="0" readonly id="subtotal">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="discount">Discount</label>
                                                    <input type="text" name="discount" id="discount" onkeyup="calculation()" value="0" class="form-control" placeholder="Type here">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="grandtotal">Grand Total</label>
                                                    <input type="number" step="0.01" name="grandtotal" class="form-control" placeholder="0" readonly id="grandtotal">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="paid">Paid</label>
                                                    <input type="number" step="0.01" name="paid" oninput="calculation()" value="0" class="form-control" placeholder="0" id="paid">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="due">Due</label>
                                                    <input type="number" step="0.01" name="due" class="form-control" placeholder="0" readonly id="due">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <h4>Attached Documents</h4>
                                            </div>
                                            <div class="col-12">
                                                <div  class="table-responsive">
                                                <h4></h4>
                                                <table class="table input_fields_wrap">
                                                    {{-- <div class="input_fields_wrap"> --}}
                                                        <tr>
                                                            <td>
                                                                <div class="form-group">
                                                                        @foreach ($documents as $item)
                                                                        <div class="custom-control custom-checkbox" style="margin-right: 20px">
                                                                            <input class="custom-control-input" name="documents[]" type="checkbox" id="document{{$item->id}}" value="{{$item->id}}" @if(isset($preselectedDocuments) && in_array($item->id, $preselectedDocuments)) checked @endif>
                                                                            <label for="document{{$item->id}}" class="custom-control-label">{{$item->name}}</label>
                                                                        </div>

                                                                        {{-- <input type="checkbox" name="documents[]" id="" value="{{$item->id}}" class="form-check-input">{{$item->name}} --}}
                                                                        @endforeach
                                                                    </select>

                                                                    {{-- <select name="documents[]" class="form-control">
                                                                        @foreach ($documents as $item)
                                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                                        @endforeach
                                                                    </select> --}}

                                                                </div>
                                                            </td>
                                                            {{-- <td>
                                                                <button type="button" class="btn btn-primary add_field_button">Add</button>
                                                            </td> --}}
                                                            {{-- <td>
                                                                <button class="btn btn-primary add_field_button">Remove</button>
                                                            </td> --}}
                                                        </tr>
                                                    {{-- </div> --}}
                                                </table>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('/admin/css/summernote-bs4.min.css') }}">
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        var studentList = document.getElementById('StudentList');
        if (studentList) {
            studentList.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                document.getElementById('first_name').value = selected.dataset.first_name || '';
                document.getElementById('last_name').value = selected.dataset.last_name || '';
                document.getElementById('email').value = selected.dataset.email || '';
                document.getElementById('address').value = selected.dataset.address || '';
                document.getElementById('mobile_no').value = selected.dataset.mobile_no || '';
            });
        }
    });

    document.getElementById('datePicker').valueAsDate = new Date();

    function parseNumber(value) {
        if (value === null || value === undefined) {
            return 0;
        }
        var normalized = value.toString().replace(/,/g, '').trim();
        var parsed = parseFloat(normalized);
        return Number.isFinite(parsed) ? parsed : 0;
    }

    function calculation(){
        let subtotal = 0;

        $('input[id^="Price"]').each(function () {
            let val = parseNumber($(this).val());
            subtotal += val;
        });

        let paid = parseNumber($('#paid').val());
        let discount = parseNumber($('#discount').val());
        let grandtotal = subtotal - discount;
        let due = grandtotal - paid;

        $('#subtotal').val(subtotal.toFixed(2));
        $('#grandtotal').val(grandtotal.toFixed(2));
        $('#due').val(due.toFixed(2));
    }

    function RemoveSec(id){
        $('#item'+id).hide();
    }

    $(document).ready(function() {
        var feeRowIndex = 0;
        var $feeSelect = $('#feeProductSelect');
        var $feesRows = $('#feesRows');

        $('#addFeeBtn').on('click', function () {
            var $option = $feeSelect.find('option:selected');
            var productId = $option.val();
            var productName = $option.data('name') || $option.text();

            if (!productId) {
                alert('Please select a fee first.');
                return;
            }

            feeRowIndex++;
            var priceId = 'Price' + feeRowIndex;

            var $row = $('<tr class="fee-row"></tr>');
            var $nameCell = $('<td></td>');
            $nameCell.append($('<input>', { type: 'hidden', name: 'ids[]', value: productId }));
            $nameCell.append($('<input>', { type: 'hidden', name: 'product[]', value: productName }));
            $nameCell.append($('<input>', { type: 'text', class: 'form-control', value: productName, readonly: true }));

            var $priceCell = $('<td></td>');
            $priceCell.append($('<input>', {
                type: 'number',
                step: '0.01',
                name: 'price[]',
                value: '0',
                class: 'form-control',
                placeholder: 'Amount',
                id: priceId,
                oninput: 'calculation()'
            }));

            var $actionCell = $('<td style="width: 60px;"></td>');
            $actionCell.append(
                $('<button>', {
                    type: 'button',
                    class: 'btn btn-danger remove-fee-btn',
                    title: 'Remove fee',
                    html: '<i class="fas fa-minus"></i>'
                })
            );

            $row.append($nameCell, $priceCell, $actionCell);
            $feesRows.append($row);
            $feeSelect.val('');
            calculation();
        });

        $feesRows.on('click', '.remove-fee-btn', function () {
            $(this).closest('tr').remove();
            calculation();
        });

        var max_fields      = 50;
        var wrapper         = $(".input_fields_wrap");
        var add_button      = $(".add_field_button");

        var x = 1;
        $(add_button).click(function(e){
            e.preventDefault();
            if(x < max_fields){
                x++;
                $(wrapper).append('<tr id="item'+ x + '"> <td> <div> <select name="documents[]" class="form-control"> @foreach ($documents as $item) <option value="{{$item->id}}">{{$item->name}}</option> @endforeach </select> </div> </td> <td> <button type="button" onclick="RemoveSec('+x+')" class="btn btn-primary add_field_button">Remove</button> </td> </tr>');
            }
        });

        $(wrapper).on("click",".remove_field", function(e){
            e.preventDefault(); $(this).parent('div').remove(); x--;
        });
    });
</script>
@endsection
