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
                            <a href="{{ route($url.'.index') }}" class="btn btn-primary">Go Back to {{$type}} List</a>
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
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                            Currency:
                                            <select name="currency" disabled>
                                                @foreach($currencies as $item)
                                                <option value="{{$item->id}}">{{$item->code}}</option>
                                                @endforeach
                                                <input type="hidden" name="currency" value="1">
                                            </select>
                                            {{-- <select name="rate">
                                                @foreach($currencies as $item)
                                                <option value="{{$item->latestRate->rate}}">{{$item->latestRate->rate}}</option>
                                                @endforeach
                                            </select> --}}
                                            </div><br>
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
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <div  class="table-responsive">
                                                    <table class="table">
                                                        @foreach ($products as $key => $item)
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="ids[]" value="{{$item->id}}" class="form-control">

                                                                    <select name="product[]" class="form-control">
                                                                        <option value="{{$item->name}}">{{$item->name}}</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="price[]" onkeyup="calculation()" value="0" class="form-control" placeholder="Amount" id="Price{{$key+1}}">
                                                                </td>
                                                            </tr>
                                                        @endforeach
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
                                                    <input type="text" name="grandtotal" class="form-control" placeholder="0" readonly id="grandtotal">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="paid">Paid</label>
                                                    <input type="text" name="paid" onkeyup="calculation()" value="0" class="form-control" placeholder="0" id="paid">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label for="due">Due</label>
                                                    <input type="text" name="due" class="form-control" placeholder="0" readonly id="due">
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
                                                                            <input class="custom-control-input" name="documents[]" type="checkbox" id="document{{$item->id}}" value="{{$item->id}}">
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
        document.getElementById('StudentList').addEventListener('change', function () {
            console.log('mk');
            const selected = this.options[this.selectedIndex];
            console.log(selected);
            console.log(selected.dataset.first_name);
            document.getElementById('first_name').value = selected.dataset.first_name || '';
            document.getElementById('last_name').value = selected.dataset.last_name || '';
            document.getElementById('email').value = selected.dataset.email || '';
            document.getElementById('address').value = selected.dataset.address || '';
            document.getElementById('mobile_no').value = selected.dataset.mobile_no || '';
        });
    });

    document.getElementById('datePicker').valueAsDate = new Date();
    function calculation(){

        let subtotal = 0;

        // Loop over all inputs with IDs starting with "Price"
        $('input[id^="Price"]').each(function () {
            let val = parseInt($(this).val()) || 0;
            subtotal += val;
        });

        // let price1 = parseInt($('#Price1').val());
        // let price2 = parseInt($('#Price2').val());
        let paid = parseInt($('#paid').val());
        let discount = parseInt($('#discount').val());
        // let subtotal = eval(price1+price2);
        let grandtotal = eval(subtotal-discount);
        let due = eval(grandtotal-paid);
        $('#subtotal').val(subtotal);
        $('#grandtotal').val(grandtotal);
        $('#due').val(due);
    }
    function RemoveSec(id){
        $('#item'+id).hide();
    }
   $(document).ready(function() {
    var max_fields      = 50; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<tr id="item'+ x + '"> <td> <div> <select name="documents[]" class="form-control"> @foreach ($documents as $item) <option value="{{$item->id}}">{{$item->name}}</option> @endforeach </select> </div> </td> <td> <button type="button" onclick="RemoveSec('+x+')" class="btn btn-primary add_field_button">Remove</button> </td> </tr>'); //add input box
        }
    });

    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});
</script>
@endsection
