@extends('layouts.default')

@section('content')
<!--begin::Dashboard-->

@php
$new=$running=$finished=$applied=0;
$allowedRoles = [1, 2, 3];
@endphp

<!--begin::Dashboard-->

<div class="row mb-5">
   @if (in_array(auth()->user()->role_id, $allowedRoles))
      <div class="col-md-3 mb-3">
      <div class="card-counter bg-success">
         <a href="{{ route('todo.index') }}"><i class="fa fas fa-layer-group text-white"></i></a>
         <span class="count-numbers"><a href="{{ route('todo.index') }}">{{$paperSubmitted}}</a></span>
         <span class="count-name"><a href="{{ route('todo.index') }}">Paper Submitted</a></span>
      </div>
   </div>
   <div class="col-md-3 mb-3">
      <div class="card-counter bg-danger">
         <a href="{{ route('todo.index') }}"><i class="fa fas fa-users text-white"></i></a>
         <span class="count-numbers"><a href="{{ route('todo.index') }}">{{$AdmissionCancel}}</a></span>
         <span class="count-name"><a href="{{ route('todo.index') }}">Admission Cancel</a></span>
      </div>
   </div>
   <div class="col-md-3 mb-3">
      <div class="card-counter bg-danger">
         <a href="{{ route('todo.index') }}"><i class="fa fas fa-users text-white"></i></a>
         <span class="count-numbers"><a href="{{ route('todo.index') }}">{{$Rejected}}</a></span>
         <span class="count-name"><a href="{{ route('todo.index') }}">Rejected</a></span>
      </div>
   </div>
   @endif
   <div class="col-md-3 mb-3">
      <div class="card-counter bg-danger">
         <a href="{{ route('todo.index') }}"><i class="fa fas fa-users text-white"></i></a>
         <span class="count-numbers"><a href="{{ route('students.index') }}">{{$allStudent}}</a></span>
         <span class="count-name"><a href="{{ route('students.index') }}">All Student</a></span>
      </div>
   </div>
   {{-- <div class="col-md-3 mb-3">
      <div class="card-counter bg-warning">
         <a href="{{ route('todo.index') }}"><i class="fa fas fa-users text-white"></i></a>
         <span class="count-numbers"><a href="{{ route('todo.index') }}">{{$totalStudents}}</a></span>
         <span class="count-name"><a href="{{ route('todo.index') }}">Total Student</a></span>
      </div>
   </div> --}}
   @if (in_array(auth()->user()->role_id, $allowedRoles))
   <div class="col-md-3 mb-3">
      <div class="card-counter bg-success">
         <a href="{{ route('todo.index') }}"><i class="fa fas fa-home text-white"></i></a>
         <span class="count-numbers"><a href="{{ route('todo.index') }}">{{$universities}}</a></span>
         <span class="count-name"><a href="{{ route('todo.index') }}">Universities</a></span>
      </div>
   </div>
   @endif
   <div class="col-md-3 mb-3">
      <div class="card-counter bg-primary">
         <a href="{{ route('user-management.index') }}"><i class="fa fas fa-users text-white"></i></a>
         <span class="count-numbers"><a href="{{ route('user-management.index') }}">{{$total_user}}</a></span>
         <span class="count-name"><a href="{{ route('user-management.index') }}">Total User</a></span>
      </div>
   </div>
   {{-- <div class="col-md-3 mb-3">
      <div class="card-counter bg-success">
         <a href="{{ route('todo.index') }}"><i class="fa fas fa-layer-group text-white"></i></a>
         <span class="count-numbers"><a href="{{ route('todo.index') }}">{{$total_todo}}</a></span>
         <span class="count-name"><a href="{{ route('todo.index') }}">Total Todo</a></span>
      </div>
   </div> --}}
</div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<style type="text/css">
/*highchart css*/

   .highcharts-figure, .highcharts-data-table table {
     /*min-width: 310px; */
     /*max-width: 1000px;*/
     /*margin: 1em auto;*/
  }

  #container {
     /*height: 400px;*/
  }

  .highcharts-data-table table {
   font-family: Verdana, sans-serif;
   border-collapse: collapse;
   border: 1px solid #EBEBEB;
   margin: 10px auto;
   text-align: center;
   width: 100%;
   /*max-width: 500px;*/
}
.highcharts-data-table caption {
  padding: 1em 0;
  font-size: 1.2em;
  color: #555;
}
.highcharts-data-table th {
   font-weight: 600;
   padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
  padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
  background: #f8f8f8;
}
.highcharts-data-table tr:hover {
  background: #f1f7ff;
}


/*Pie chart*/
.highcharts-figure, .highcharts-data-table table {
 min-width: 320px;
 max-width: 1030px;
 margin: 1em auto;
}

.highcharts-data-table table {
   font-family: Verdana, sans-serif;
   border-collapse: collapse;
   border: 1px solid #EBEBEB;
   margin: 10px auto;
   text-align: center;
   width: 100%;
   max-width: 500px;
}
.highcharts-data-table caption {
 padding: 1em 0;
 font-size: 1.2em;
 color: #555;
}
.highcharts-data-table th {
   font-weight: 600;
   padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
 padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
 background: #f8f8f8;
}
.highcharts-data-table tr:hover {
 background: #f1f7ff;
}


input[type="number"] {
   min-width: 50px;
}
</style>

@endsection
@section('styles')
<link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
<script src="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('js/pages/widgets.js') }}"></script>
@endsection


