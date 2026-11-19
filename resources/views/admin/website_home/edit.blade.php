@extends('layouts.default')

@section('content')
@php
    $hero = $sections['hero'];
    $stats = $sections['stats'];
    $why = $sections['why'];
    $how = $sections['how'];
    $mid = $sections['mid_cta'];
    $final = $sections['final_cta'];
    $faq = $sections['faq'];
    $ask = $sections['ask_experts'];
    $toolsBanner = $sections['tools_banner'] ?? [
        'enabled' => true,
        'eyebrow' => '',
        'title' => '',
        'subtitle' => '',
        'cta_label' => '',
        'cta_url' => '',
        'image' => '',
        'mobile_image' => '',
    ];
    $visibility = $sections['visibility'] ?? [];
    $visibilityLabels = [
        'hero' => 'Hero',
        'stats' => 'Stats strip',
        'popular_courses' => 'Popular courses',
        'popular_universities' => 'Popular universities',
        'tools' => 'Tools (Plan your China journey)',
        'tools_banner' => 'Tools full-width banner',
        'ask_experts' => 'Ask experts',
        'services' => 'Services',
        'degrees' => 'Degree pathways',
        'fields' => 'Fields / majors',
        'scholarships' => 'Scholarships',
        'mid_cta' => 'Mid CTA',
        'why' => 'Why China',
        'how' => 'How it works',
        'gallery' => 'Success photos gallery',
        'videos' => 'Video gallery',
        'faq' => 'FAQ',
        'final_cta' => 'Final CTA',
    ];
    $tools = $sections['tools'] ?? [];
    $degrees = $sections['degrees'] ?? [];
    $fieldGroups = $sections['field_groups'] ?? [];
    $servicesCatalog = $sections['services_catalog'] ?? [];
@endphp
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Home Page Content</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        @include('includes.errors')
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

                <form method="POST" action="{{ route('home-content.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

            <div class="card">
                <div class="card-header"><h3 class="card-title">Section show / hide</h3></div>
                <div class="card-body">
                    <p class="text-muted mb-3">Turn homepage sections on or off without deleting content.</p>
                    <div class="row">
                        @foreach($visibilityLabels as $key => $label)
                            <div class="col-md-4 col-sm-6">
                                <div class="custom-control custom-switch mb-3">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="visibility_{{ $key }}"
                                        name="visibility_{{ $key }}"
                                        value="1"
                                        {{ old('visibility_'.$key, $visibility[$key] ?? true) ? 'checked' : '' }}
                                    >
                                    <label class="custom-control-label" for="visibility_{{ $key }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Tools list</h3></div>
                <div class="card-body">
                    @foreach($tools as $i => $tool)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="tool_title[]" class="form-control" value="{{ old('tool_title.'.$i, $tool['title'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>URL</label>
                                        <input type="text" name="tool_to[]" class="form-control" value="{{ old('tool_to.'.$i, $tool['to'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Icon</label>
                                        <input type="text" name="tool_icon[]" class="form-control" value="{{ old('tool_icon.'.$i, $tool['icon'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="tool_desc[]" class="form-control" value="{{ old('tool_desc.'.$i, $tool['desc'] ?? '') }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label>Color</label>
                                        <input type="text" name="tool_color[]" class="form-control" value="{{ old('tool_color.'.$i, $tool['color'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label>Background</label>
                                        <input type="text" name="tool_bg[]" class="form-control" value="{{ old('tool_bg.'.$i, $tool['bg'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Degree pathways</h3></div>
                <div class="card-body">
                    @foreach($degrees as $i => $degree)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="degree_name[]" class="form-control" value="{{ old('degree_name.'.$i, $degree['name'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Slug</label>
                                        <input type="text" name="degree_slug[]" class="form-control" value="{{ old('degree_slug.'.$i, $degree['slug'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Letter</label>
                                        <input type="text" name="degree_letter[]" class="form-control" value="{{ old('degree_letter.'.$i, $degree['letter'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Label</label>
                                        <input type="text" name="degree_label[]" class="form-control" value="{{ old('degree_label.'.$i, $degree['label'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Fields / majors groups</h3></div>
                <div class="card-body">
                    <div class="form-group mb-0">
                        <label>One field group per line</label>
                        <textarea name="field_groups" rows="10" class="form-control">{{ old('field_groups', implode("\n", $fieldGroups)) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Services catalog (fallback if API empty)</h3></div>
                <div class="card-body">
                    <p class="text-muted">Used when service posts are unavailable. Prefer managing Services from Website → Services.</p>
                    @foreach($servicesCatalog as $i => $service)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="service_name[]" class="form-control" value="{{ old('service_name.'.$i, $service['name'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Slug</label>
                                        <input type="text" name="service_slug[]" class="form-control" value="{{ old('service_slug.'.$i, $service['slug'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Icon</label>
                                        <input type="text" name="service_icon[]" class="form-control" value="{{ old('service_icon.'.$i, $service['icon'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Short text</label>
                                <input type="text" name="service_short[]" class="form-control" value="{{ old('service_short.'.$i, $service['short'] ?? '') }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label>Color</label>
                                        <input type="text" name="service_color[]" class="form-control" value="{{ old('service_color.'.$i, $service['color'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label>Background</label>
                                        <input type="text" name="service_bg[]" class="form-control" value="{{ old('service_bg.'.$i, $service['bg'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Hero</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Headline</label>
                        <textarea name="hero_headline" rows="2" class="form-control">{{ old('hero_headline', $hero['headline']) }}</textarea>
                        <small class="text-muted">Use a new line for a line break on the website.</small>
                    </div>
                    <div class="form-group">
                        <label>Subheadline</label>
                        <textarea name="hero_subheadline" rows="2" class="form-control">{{ old('hero_subheadline', $hero['subheadline']) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Primary button label</label>
                                <input type="text" name="hero_primary_cta_label" class="form-control" value="{{ old('hero_primary_cta_label', $hero['primary_cta_label']) }}">
                            </div>
                            <div class="form-group">
                                <label>Primary button URL</label>
                                <input type="text" name="hero_primary_cta_url" class="form-control" value="{{ old('hero_primary_cta_url', $hero['primary_cta_url']) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Secondary button label</label>
                                <input type="text" name="hero_secondary_cta_label" class="form-control" value="{{ old('hero_secondary_cta_label', $hero['secondary_cta_label']) }}">
                            </div>
                            <div class="form-group">
                                <label>Secondary button URL</label>
                                <input type="text" name="hero_secondary_cta_url" class="form-control" value="{{ old('hero_secondary_cta_url', $hero['secondary_cta_url']) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Search placeholder</label>
                        <input type="text" name="hero_search_placeholder" class="form-control" value="{{ old('hero_search_placeholder', $hero['search_placeholder']) }}">
                    </div>
                    <div class="form-group mb-0">
                        <label>Background image URLs (one per line)</label>
                        <textarea name="hero_slides" rows="6" class="form-control">{{ old('hero_slides', implode("\n", $hero['slides'])) }}</textarea>
                        <small class="text-muted">Example: /images/hero-bg-1.jpg or a full https image URL.</small>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Stats strip</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Eyebrow</label>
                                <input type="text" name="stats_eyebrow" class="form-control" value="{{ old('stats_eyebrow', $stats['eyebrow']) }}">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Intro</label>
                                <input type="text" name="stats_intro" class="form-control" value="{{ old('stats_intro', $stats['intro']) }}">
                            </div>
                        </div>
                    </div>
                    @foreach($stats['items'] as $i => $item)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Value {{ $i + 1 }}</label>
                                    <input type="text" name="stat_value[]" class="form-control" value="{{ old('stat_value.'.$i, $item['value']) }}">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Label {{ $i + 1 }}</label>
                                    <input type="text" name="stat_label[]" class="form-control" value="{{ old('stat_label.'.$i, $item['label']) }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Why China</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Eyebrow</label>
                        <input type="text" name="why_eyebrow" class="form-control" value="{{ old('why_eyebrow', $why['eyebrow']) }}">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="why_section_title" class="form-control" value="{{ old('why_section_title', $why['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <textarea name="why_subtitle" rows="2" class="form-control">{{ old('why_subtitle', $why['subtitle']) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Summary label</label>
                                <input type="text" name="why_summary_label" class="form-control" value="{{ old('why_summary_label', $why['summary_label']) }}">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Summary title</label>
                                <input type="text" name="why_summary_title" class="form-control" value="{{ old('why_summary_title', $why['summary_title']) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Summary text</label>
                        <textarea name="why_summary_text" rows="2" class="form-control">{{ old('why_summary_text', $why['summary_text']) }}</textarea>
                    </div>
                    <hr>
                    @foreach($why['items'] as $i => $item)
                        <div class="border rounded p-3 mb-3">
                            <div class="form-group">
                                <label>Card title {{ $i + 1 }}</label>
                                <input type="text" name="why_title[]" class="form-control" value="{{ old('why_title.'.$i, $item['title']) }}">
                            </div>
                            <div class="form-group">
                                <label>Card text</label>
                                <textarea name="why_desc[]" rows="2" class="form-control">{{ old('why_desc.'.$i, $item['desc']) }}</textarea>
                            </div>
                            <div class="form-group mb-0">
                                <label>Icon class (Tabler)</label>
                                <input type="text" name="why_icon[]" class="form-control" value="{{ old('why_icon.'.$i, $item['icon']) }}" placeholder="ti-wallet">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">How it works</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Eyebrow</label>
                        <input type="text" name="how_eyebrow" class="form-control" value="{{ old('how_eyebrow', $how['eyebrow']) }}">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="how_section_title" class="form-control" value="{{ old('how_section_title', $how['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <textarea name="how_subtitle" rows="2" class="form-control">{{ old('how_subtitle', $how['subtitle']) }}</textarea>
                    </div>
                    <hr>
                    @foreach($how['items'] as $i => $item)
                        <div class="border rounded p-3 mb-3">
                            <div class="form-group">
                                <label>Step title {{ $i + 1 }}</label>
                                <input type="text" name="how_title[]" class="form-control" value="{{ old('how_title.'.$i, $item['title']) }}">
                            </div>
                            <div class="form-group">
                                <label>Step text</label>
                                <textarea name="how_desc[]" rows="2" class="form-control">{{ old('how_desc.'.$i, $item['desc']) }}</textarea>
                            </div>
                            <div class="form-group mb-0">
                                <label>Icon class (Tabler)</label>
                                <input type="text" name="how_icon[]" class="form-control" value="{{ old('how_icon.'.$i, $item['icon']) }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Ask experts</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Eyebrow</label>
                        <input type="text" name="ask_eyebrow" class="form-control" value="{{ old('ask_eyebrow', $ask['eyebrow']) }}">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="ask_title" class="form-control" value="{{ old('ask_title', $ask['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <textarea name="ask_subtitle" rows="2" class="form-control">{{ old('ask_subtitle', $ask['subtitle']) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Quick questions (one per line)</label>
                        <textarea name="ask_quick_questions" rows="6" class="form-control">{{ old('ask_quick_questions', implode("\n", $ask['quick_questions'])) }}</textarea>
                    </div>
                    @foreach($ask['experts'] as $i => $expert)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Initials</label>
                                        <input type="text" name="expert_initials[]" class="form-control" value="{{ old('expert_initials.'.$i, $expert['initials']) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="expert_name[]" class="form-control" value="{{ old('expert_name.'.$i, $expert['name']) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Role</label>
                                        <input type="text" name="expert_role[]" class="form-control" value="{{ old('expert_role.'.$i, $expert['role']) }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Color</label>
                                        <input type="text" name="expert_color[]" class="form-control" value="{{ old('expert_color.'.$i, $expert['color']) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Tools section banner (full width under “Plan your China journey”)</h3></div>
                <div class="card-body">
                    <p class="text-muted">Show/hide this banner from the <strong>Section show / hide</strong> card above.</p>
                    <div class="form-group">
                        <label>Eyebrow</label>
                        <input type="text" name="tools_banner_eyebrow" class="form-control" value="{{ old('tools_banner_eyebrow', $toolsBanner['eyebrow'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="tools_banner_title" class="form-control" value="{{ old('tools_banner_title', $toolsBanner['title'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <textarea name="tools_banner_subtitle" rows="2" class="form-control">{{ old('tools_banner_subtitle', $toolsBanner['subtitle'] ?? '') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Button label</label>
                                <input type="text" name="tools_banner_cta_label" class="form-control" value="{{ old('tools_banner_cta_label', $toolsBanner['cta_label'] ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Button URL</label>
                                <input type="text" name="tools_banner_cta_url" class="form-control" value="{{ old('tools_banner_cta_url', $toolsBanner['cta_url'] ?? '') }}" placeholder="/contact">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Desktop image URL</label>
                                <input type="text" name="tools_banner_image" class="form-control" value="{{ old('tools_banner_image', $toolsBanner['image'] ?? '') }}" placeholder="/images/hero-bg-3.jpg">
                            </div>
                            <div class="form-group">
                                <label>Or upload desktop image</label>
                                <input type="file" name="tools_banner_image_file" class="form-control-file" accept="image/*">
                            </div>
                            @if(!empty($toolsBanner['image']))
                                <img src="{{ $toolsBanner['image'] }}" alt="" style="max-width:100%;max-height:140px;object-fit:cover;border-radius:8px;">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile image URL (optional)</label>
                                <input type="text" name="tools_banner_mobile_image" class="form-control" value="{{ old('tools_banner_mobile_image', $toolsBanner['mobile_image'] ?? '') }}">
                            </div>
                            <div class="form-group">
                                <label>Or upload mobile image</label>
                                <input type="file" name="tools_banner_mobile_image_file" class="form-control-file" accept="image/*">
                            </div>
                            @if(!empty($toolsBanner['mobile_image']))
                                <img src="{{ $toolsBanner['mobile_image'] }}" alt="" style="max-width:100%;max-height:140px;object-fit:cover;border-radius:8px;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Mid CTA</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="mid_cta_title" class="form-control" value="{{ old('mid_cta_title', $mid['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <textarea name="mid_cta_subtitle" rows="2" class="form-control">{{ old('mid_cta_subtitle', $mid['subtitle']) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Primary button label</label>
                                <input type="text" name="mid_cta_primary_label" class="form-control" value="{{ old('mid_cta_primary_label', $mid['primary_cta_label']) }}">
                            </div>
                            <div class="form-group mb-0">
                                <label>Primary button URL</label>
                                <input type="text" name="mid_cta_primary_url" class="form-control" value="{{ old('mid_cta_primary_url', $mid['primary_cta_url']) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label>Secondary button label (WhatsApp)</label>
                                <input type="text" name="mid_cta_secondary_label" class="form-control" value="{{ old('mid_cta_secondary_label', $mid['secondary_cta_label']) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">FAQ intro</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Eyebrow</label>
                        <input type="text" name="faq_eyebrow" class="form-control" value="{{ old('faq_eyebrow', $faq['eyebrow']) }}">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="faq_title" class="form-control" value="{{ old('faq_title', $faq['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <textarea name="faq_subtitle" rows="2" class="form-control">{{ old('faq_subtitle', $faq['subtitle']) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Help box label</label>
                        <input type="text" name="faq_help_label" class="form-control" value="{{ old('faq_help_label', $faq['help_label']) }}">
                    </div>
                    <div class="form-group">
                        <label>Help box text</label>
                        <textarea name="faq_help_text" rows="2" class="form-control">{{ old('faq_help_text', $faq['help_text']) }}</textarea>
                    </div>
                    <div class="form-group mb-0">
                        <label>Help button label</label>
                        <input type="text" name="faq_help_cta_label" class="form-control" value="{{ old('faq_help_cta_label', $faq['help_cta_label']) }}">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Final CTA</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="final_cta_title" class="form-control" value="{{ old('final_cta_title', $final['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <textarea name="final_cta_subtitle" rows="2" class="form-control">{{ old('final_cta_subtitle', $final['subtitle']) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Primary button (WhatsApp)</label>
                                <input type="text" name="final_cta_primary_label" class="form-control" value="{{ old('final_cta_primary_label', $final['primary_cta_label']) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Secondary button label</label>
                                <input type="text" name="final_cta_secondary_label" class="form-control" value="{{ old('final_cta_secondary_label', $final['secondary_cta_label']) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Secondary button URL</label>
                                <input type="text" name="final_cta_secondary_url" class="form-control" value="{{ old('final_cta_secondary_url', $final['secondary_cta_url']) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <button type="submit" class="btn btn-primary">Save Home Content</button>
            </div>
        </form>
    </div>
</div>
@endsection
