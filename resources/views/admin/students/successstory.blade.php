<!DOCTYPE html>
<html>
<head>
    <title>{{ $student->username }}</title>

    <!-- OPEN GRAPH TAGS -->
    <meta property="og:title" content="{{ $student->username }}">
    <meta property="og:description" content="{{ $student->username }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    {{-- <meta property="og:image" content="{{ asset($student->generated_image) }}"> --}}
    <meta property="og:image" content="{{url('/')}}/uploads/profile/{{ $student->profile_pic }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- IMPORTANT -->
    <meta name="twitter:card" content="summary_large_image">
</head>
<body>
    {{-- @if($student->profile_pic != NULL)
                  <img class="border" src="{{url('/')}}/uploads/profile/{{ $student->profile_pic }}" width="200">
               @else
                  <img src="{{url('/')}}/uploads/profile/default.jpg" width="200">
               @endif --}}
    <h1>Redirecting...</h1>
</body>
</html>
