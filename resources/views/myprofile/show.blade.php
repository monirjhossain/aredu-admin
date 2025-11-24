@extends('layouts.default')
@section('content')
<div class="card card-custom col-md-7 col-lg-7 col-sm-12">
   <div class="card-header flex-wrap py-5">
      <div class="card-title">
         <h3 class="card-label">{{ $page_title }}</h3>
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
         <span class="text-dark-100 flex-root font-weight-bold font-size-h6">Phone No:</span>
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
                  <img class="border" src="{{url('/')}}/uploads/profile/{{ $userManagement->profile_pic }}" width="200">
               @else
                  <img src="{{url('/')}}/uploads/profile/default.jpg" width="200">
               @endif
         </span>
      </div>

   </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
<script src="{{ asset('plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/pages/crud/datatables/advanced/multiple-controls.js') }}"></script>
@endsection


