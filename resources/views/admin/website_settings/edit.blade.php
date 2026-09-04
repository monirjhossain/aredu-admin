@extends('layouts.default')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Website Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Public website contact & FAQ (controls frontend)</h3>
            </div>
            <div class="card-body">
                @include('includes.errors')
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('setting.website.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Brand name</label>
                                <input type="text" name="brand" class="form-control" value="{{ old('brand', $settings['brand']) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings['phone']) }}">
                            </div>
                            <div class="form-group">
                                <label>WhatsApp link</label>
                                <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $settings['whatsapp']) }}" placeholder="https://wa.me/8801...">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $settings['email']) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" rows="3" class="form-control">{{ old('address', $settings['address']) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Facebook URL</label>
                                <input type="text" name="facebook" class="form-control" value="{{ old('facebook', $settings['facebook']) }}">
                            </div>
                            <div class="form-group">
                                <label>YouTube URL</label>
                                <input type="text" name="youtube" class="form-control" value="{{ old('youtube', $settings['youtube']) }}">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">FAQs (shown on website)</h4>
                    <div id="faq-list">
                        @foreach($faqs as $i => $faq)
                            <div class="border rounded p-3 mb-3 faq-row">
                                <div class="form-group">
                                    <label>Question</label>
                                    <input type="text" name="faq_q[]" class="form-control" value="{{ $faq['q'] ?? '' }}">
                                </div>
                                <div class="form-group mb-0">
                                    <label>Answer</label>
                                    <textarea name="faq_a[]" rows="3" class="form-control">{{ $faq['a'] ?? '' }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-light mb-4" id="add-faq">+ Add FAQ</button>

                    <div>
                        <button type="submit" class="btn btn-primary">Save Website Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('add-faq').addEventListener('click', function () {
    var wrap = document.getElementById('faq-list');
    var row = document.createElement('div');
    row.className = 'border rounded p-3 mb-3 faq-row';
    row.innerHTML = '<div class="form-group"><label>Question</label><input type="text" name="faq_q[]" class="form-control"></div>' +
        '<div class="form-group mb-0"><label>Answer</label><textarea name="faq_a[]" rows="3" class="form-control"></textarea></div>';
    wrap.appendChild(row);
});
</script>
@endsection
