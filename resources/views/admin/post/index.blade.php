@extends('layouts.default')

@php
    $isUniversity = ($type ?? '') === 'university';
    $tableColspan = $isUniversity ? 10 : 8;
@endphp

@section('content')
<div class="post-list-page">
    <div class="row post-list-page-title">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ $title }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card card-custom post-list-card">
        <div class="card-header flex-wrap py-5 px-6">
            <form id="searchForm" action="{{ route($url . '.index') }}" method="get" class="w-100 mb-3 mb-lg-0">
                <div class="row align-items-end">
                    <div class="col-md-{{ $isUniversity ? '4' : '6' }} mb-2">
                        <input type="text" name="search" placeholder="Search" class="form-control"
                            value="{{ $request->search }}">
                    </div>
                    @if ($isUniversity)
                        <div class="col-md-4 mb-2">
                            <select class="form-control" name="subject">
                                <option value="">-Select Subject-</option>
                                @foreach ($subjects ?? [] as $item)
                                    <option value="{{ $item->id }}" {{ (string) $request->subject === (string) $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <input id="print" type="hidden" name="print" value="no">
                </div>
            </form>
            <div class="card-toolbar pt-2 pt-lg-0">
                <a href="javascript:;" onclick="submitDetailsForm('no')" class="btn btn-primary mr-1">
                    <i class="la la-search"></i> Search
                </a>
                <a href="javascript:;" onclick="submitDetailsForm('yes')" class="btn btn-primary mr-1">
                    <i class="la la-print"></i> Print
                </a>
                <a href="{{ route($url . '.create') }}" class="btn btn-primary mr-1">
                    <i class="la la-plus"></i> Create {{ $type }}
                </a>
                <button type="button" onclick="getSmsTemplate(0)" class="btn btn-primary messageModal mr-1"
                    data-toggle="modal" data-target="#exampleModalLong">
                    <i class="fas fa-envelope" title="message"></i>
                </button>
            </div>
        </div>

        <div class="card-body post-list-card-body">
            <div class="post-list-table-outer">
            <table class="table table-hover post-list-table mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 40px">#</th>
                        <th style="width: 70px">Image</th>
                        <th class="post-list-th-title">Title</th>
                        @if ($isUniversity)
                            <th class="post-list-th-subjects">Subjects</th>
                            <th class="post-list-th-credentials">Credentials</th>
                        @endif
                        <th class="post-list-th-category">Category</th>
                        <th class="post-list-th-author">Author</th>
                        <th style="width: 100px">Created</th>
                        <th style="width: 80px">Status</th>
                        <th style="width: 130px; min-width: 130px;" class="text-center">
                            <i class="fa fa-bolt"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>
                                @if($post->image)
                                    <a href="{{ asset($post->image) }}" target="_blank">
                                        <img src="{{ asset($post->image) }}" alt="" class="post-list-thumb-img">
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="post-list-td-title">
                                <div class="post-list-cell-title" title="{{ $post->post_title }}">{{ $post->post_title }}</div>
                            </td>
                            @if ($isUniversity)
                                <td class="post-list-td-badges">
                                    <div class="post-list-cell-badges">
                                        @forelse($post->subjects as $sub)
                                            <span class="badge badge-primary post-list-badge">{{ $sub->name }}</span>
                                        @empty
                                            <span class="text-muted">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="post-list-td-scroll font-size-sm">
                                    <div class="post-list-cell-scroll">
                                        <div><b>Link:</b> {{ $post->getMeta('link') ?: '—' }}</div>
                                        <div><b>User:</b> {{ $post->getMeta('user') ?: '—' }}</div>
                                        <div><b>Pass:</b> {{ $post->getMeta('password') ?: '—' }}</div>
                                    </div>
                                </td>
                            @endif
                            <td class="post-list-td-badges">
                                <div class="post-list-cell-badges">
                                    @forelse($post->categories as $cat)
                                        <span class="badge badge-primary post-list-badge">{{ $cat->name }}</span>
                                    @empty
                                        <span class="text-muted">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="post-list-cell-wrap">{{ $post->user->name ?? '—' }}</td>
                            <td class="text-nowrap">{{ $post->created_at->format('d M, Y') }}</td>
                            <td>
                                <span class="badge {{ $post->status == '1' ? 'badge-primary' : 'badge-danger' }}">
                                    {{ $post->status == '1' ? 'Active' : 'Deactive' }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap post-list-td-actions">
                                <a href="{{ route($url . '.show', $post->id) }}" class="btn btn-sm btn-success mr-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route($url . '.edit', $post->id) }}" class="btn btn-sm btn-primary mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route($url . '.show', $post->id) }}" target="_blank" class="btn btn-sm btn-dark mr-1">
                                    <i class="fas fa-link"></i>
                                </a>
                                <form action="{{ route($url . '.destroy', $post->id) }}" class="d-inline-block" method="POST"
                                    onsubmit="return confirm('Delete this {{ $type }}?');">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $tableColspan }}" class="text-center py-8">
                                <h5 class="text-muted mb-0">No {{ $type }} found.</h5>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
    <link href="{{ asset('css/admin/post-index.css') }}?v=2" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
<script>
    function submitDetailsForm(type) {
        var printInput = document.querySelector('#searchForm #print');
        if (printInput) {
            printInput.value = type === 'yes' ? 'yes' : 'no';
        }
        document.getElementById('searchForm').submit();
    }
</script>
@endsection
