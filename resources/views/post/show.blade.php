@extends('layouts.default')

@section('content')

<div class="container py-5">
   <div class="row justify-content-center">
      <div class="col-lg-8">
         <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
               <h3 class="mb-0"><i class="la la-university mr-2"></i> {{$page_title}}</h3>
               <a href="{{ url('user-management') }}" class="btn btn-light btn-sm">
                  <i class="la la-arrow-left"></i> Back to List
               </a>
            </div>
            <div class="card-body p-5">
               <div class="row align-items-center">
                  <div class="col-md-4 text-center mb-4 mb-md-0">
                     @if($userManagement->profile_pic != NULL)
                        <img src="{{url('/')}}/uploads/profile/{{ $userManagement->profile_pic }}" class="img-fluid rounded-circle border border-3" style="width: 180px; height: 180px; object-fit: cover; box-shadow: 0 4px 16px rgba(0,0,0,0.10);">
                     @else
                        <img src="{{url('/')}}/uploads/profile/default.jpg" class="img-fluid rounded-circle border border-3" style="width: 180px; height: 180px; object-fit: cover; box-shadow: 0 4px 16px rgba(0,0,0,0.10);">
                     @endif
                  </div>
                  <div class="col-md-8">
                     <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent px-0 pb-2">
                           <span class="font-weight-bold text-secondary"><i class="la la-user mr-2"></i>Name:</span>
                           <span class="ml-2 h5">{{ $userManagement->name }}</span>
                        </li>
                        <li class="list-group-item bg-transparent px-0 pb-2">
                           <span class="font-weight-bold text-secondary"><i class="la la-id-badge mr-2"></i>Username:</span>
                           <span class="ml-2 h5">{{ $userManagement->username }}</span>
                        </li>
                        <li class="list-group-item bg-transparent px-0 pb-2">
                           <span class="font-weight-bold text-secondary"><i class="la la-phone mr-2"></i>Mobile No:</span>
                           <span class="ml-2 h5">{{ $userManagement->mobile_no }}</span>
                        </li>
                        <li class="list-group-item bg-transparent px-0 pb-2">
                           <span class="font-weight-bold text-secondary"><i class="la la-envelope mr-2"></i>Email:</span>
                           <span class="ml-2 h5">{{ $userManagement->email }}</span>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

@endsection

{{-- Includable CSS Related Page --}}

@section('styles')
<!-- FontAwesome/LineAwesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css" rel="stylesheet">
<style>
   .card {
      border-radius: 1rem;
   }
   .card-header {
      border-radius: 1rem 1rem 0 0;
   }
   .list-group-item {
      border: none;
      font-size: 1.1rem;
   }
   .btn-light {
      color: #333;
      background: #f8f9fa;
      border: 1px solid #e2e6ea;
      transition: background 0.2s;
   }
   .btn-light:hover {
      background: #e2e6ea;
   }
</style>
@endsection

{{-- Scripts Section Related Page--}}

@section('scripts')
<!-- No additional scripts needed for this design -->
@endsection


