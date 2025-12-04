@extends('layouts.default')

@section('content')
<!--begin::Card-->
<div class="card card-custom col-md-7 col-lg-7 col-sm-12">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h3 class="card-label"> {{$page_title}} </h3>
      </div>
      <div class="card-toolbar">
         <a href="{{ url('branches') }}" class="btn btn-sm btn-primary font-weight-bolder">
            <i class="la la-list"></i> Go TO User List
         </a>
      </div>
   </div>
   <div class="card-body">
      <div class="d-flex mb-3">
         <span class="text-dark-100 flex-root font-weight-bold font-size-h6">Name:</span>
         <span class="text-dark flex-root font-weight-bolder font-size-h6">{{ $userManagement->name}}</span>
      </div>
      <div class="d-flex mb-3">
         <span class="text-dark-100 flex-root font-weight-bold font-size-h6">Username:</span>
         <span class="text-dark flex-root font-weight-bolder font-size-h6">{{ $userManagement->username}}</span>
      </div>
      <div class="d-flex mb-3">
         <span class="text-dark-100 flex-root font-weight-bold font-size-h6">Mobile No:</span>
         <span class="text-dark flex-root font-weight-bolder font-size-h6">{{ $userManagement->mobile_no}}</span>
      </div>
      <div class="d-flex mb-3">
         <span class="text-dark-100 flex-root font-weight-bold font-size-h6">Email:</span>
         <span class="text-dark flex-root font-weight-bolder font-size-h6">{{ $userManagement->email}}</span>
      </div>
      <div class="d-flex mb-3">
         <span class="text-dark-100 flex-root font-weight-bold font-size-h6">Profile Picture:</span>
         <span class="text-dark flex-root font-weight-bolder font-size-h6">
               @if($userManagement->profile_pic != NULL)
                  <img src="{{url('/')}}/uploads/profile/{{ $userManagement->profile_pic }}" width="200">
               @else
                  <img src="{{url('/')}}/uploads/profile/default.jpg" width="200">
               @endif

         </span>
         <!-- <img src="uploads/signature/{{ $userManagement->signature }}" width="300" height="300"> -->
      </div>
   </div>
</div>
<!--end::Card-->

@endsection

{{-- Includable CSS Related Page --}}
@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Vendors Styles-->
@endsection

{{-- Scripts Section Related Page--}}
@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
<!--end::Page Scripts-->
@endsection


