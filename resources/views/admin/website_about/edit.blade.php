@extends('layouts.default')

@section('content')
@php
    $hero = $about['hero'];
    $story = $about['story'];
    $values = $about['values'];
    $journey = $about['journey'];
    $team = $about['team'];
    $trust = $about['trust'];
    $cta = $about['cta'];
    $visibility = $about['visibility'];
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
                    <li class="breadcrumb-item active">About Page Content</li>
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

        <form method="POST" action="{{ route('about-content.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header"><h3 class="card-title">Section show / hide</h3></div>
                <div class="card-body">
                    <p class="text-muted mb-3">Turn About page sections on or off without deleting content. Hero always stays visible.</p>
                    <div class="row">
                        @foreach($visibilityLabels as $key => $label)
                            <div class="col-md-4 col-sm-6">
                                <div class="custom-control custom-switch mb-3">
                                    <input type="hidden" name="visibility_{{ $key }}" value="0">
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
                <div class="card-header"><h3 class="card-title">Hero</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Eyebrow</label>
                        <input type="text" name="hero_eyebrow" class="form-control" value="{{ old('hero_eyebrow', $hero['eyebrow']) }}">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $hero['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <textarea name="hero_subtitle" rows="4" class="form-control">{{ old('hero_subtitle', $hero['subtitle']) }}</textarea>
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
                                <label>Secondary button label (WhatsApp)</label>
                                <input type="text" name="hero_secondary_cta_label" class="form-control" value="{{ old('hero_secondary_cta_label', $hero['secondary_cta_label']) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach(['1','2','3'] as $n)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Collage image {{ $n }} URL</label>
                                    <input type="text" name="hero_image_{{ $n }}" class="form-control" value="{{ old('hero_image_'.$n, $hero['image_'.$n] ?? '') }}">
                                </div>
                                <div class="form-group">
                                    <label>Or upload image {{ $n }}</label>
                                    <input type="file" name="hero_image_{{ $n }}_file" class="form-control-file" accept="image/*">
                                </div>
                                @if(!empty($hero['image_'.$n]))
                                    <img src="{{ $hero['image_'.$n] }}" alt="" style="max-width:100%;max-height:100px;object-fit:cover;border-radius:8px;">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Stats</h3></div>
                <div class="card-body">
                    <p class="text-muted">Leave a row blank to hide it. Extra empty rows let you add more.</p>
                    @foreach($statsRows as $i => $item)
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
                <div class="card-header"><h3 class="card-title">Our story</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Eyebrow</label>
                        <input type="text" name="story_eyebrow" class="form-control" value="{{ old('story_eyebrow', $story['eyebrow']) }}">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="story_title" class="form-control" value="{{ old('story_title', $story['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Body (paragraphs — blank line = new paragraph)</label>
                        <textarea name="story_body" rows="7" class="form-control">{{ old('story_body', $story['body']) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Quote</label>
                                <input type="text" name="story_quote" class="form-control" value="{{ old('story_quote', $story['quote']) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Quote author</label>
                                <input type="text" name="story_quote_author" class="form-control" value="{{ old('story_quote_author', $story['quote_author']) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Story image URL</label>
                        <input type="text" name="story_image" class="form-control" value="{{ old('story_image', $story['image'] ?? '') }}">
                    </div>
                    <div class="form-group mb-0">
                        <label>Or upload story image</label>
                        <input type="file" name="story_image_file" class="form-control-file" accept="image/*">
                    </div>
                    @if(!empty($story['image']))
                        <img src="{{ $story['image'] }}" alt="" class="mt-3" style="max-width:220px;max-height:140px;object-fit:cover;border-radius:8px;">
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Values</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Eyebrow</label>
                        <input type="text" name="values_eyebrow" class="form-control" value="{{ old('values_eyebrow', $values['eyebrow']) }}">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="values_title" class="form-control" value="{{ old('values_title', $values['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <input type="text" name="values_subtitle" class="form-control" value="{{ old('values_subtitle', $values['subtitle']) }}">
                    </div>
                    @foreach($valueRows as $i => $item)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Title {{ $i + 1 }}</label>
                                        <input type="text" name="value_title[]" class="form-control" value="{{ old('value_title.'.$i, $item['title']) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Icon (Tabler class)</label>
                                        <input type="text" name="value_icon[]" class="form-control" value="{{ old('value_icon.'.$i, $item['icon'] ?? 'ti-check') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <input type="text" name="value_description[]" class="form-control" value="{{ old('value_description.'.$i, $item['description'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">How we help (journey)</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Eyebrow</label>
                        <input type="text" name="journey_eyebrow" class="form-control" value="{{ old('journey_eyebrow', $journey['eyebrow']) }}">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="journey_title_section" class="form-control" value="{{ old('journey_title_section', $journey['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <input type="text" name="journey_subtitle" class="form-control" value="{{ old('journey_subtitle', $journey['subtitle']) }}">
                    </div>
                    @foreach($journeyRows as $i => $item)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Step title {{ $i + 1 }}</label>
                                        <input type="text" name="journey_title[]" class="form-control" value="{{ old('journey_title.'.$i, $item['title']) }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Icon</label>
                                        <input type="text" name="journey_icon[]" class="form-control" value="{{ old('journey_icon.'.$i, $item['icon'] ?? 'ti-circle-check') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <input type="text" name="journey_description[]" class="form-control" value="{{ old('journey_description.'.$i, $item['description'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Team</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Eyebrow</label>
                        <input type="text" name="team_eyebrow" class="form-control" value="{{ old('team_eyebrow', $team['eyebrow']) }}">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="team_title" class="form-control" value="{{ old('team_title', $team['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <input type="text" name="team_subtitle" class="form-control" value="{{ old('team_subtitle', $team['subtitle'] ?? '') }}">
                    </div>
                    @foreach($teamRows as $i => $member)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Initials</label>
                                        <input type="text" name="team_initials[]" class="form-control" value="{{ old('team_initials.'.$i, $member['initials']) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="team_name[]" class="form-control" value="{{ old('team_name.'.$i, $member['name']) }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Role</label>
                                        <input type="text" name="team_role[]" class="form-control" value="{{ old('team_role.'.$i, $member['role']) }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Color</label>
                                        <input type="text" name="team_color[]" class="form-control" value="{{ old('team_color.'.$i, $member['color']) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Short bio</label>
                                <input type="text" name="team_bio[]" class="form-control" value="{{ old('team_bio.'.$i, $member['bio'] ?? '') }}">
                            </div>
                            <div class="form-group mb-0">
                                <label>Photo URL</label>
                                <input type="text" name="team_photo[]" class="form-control" value="{{ old('team_photo.'.$i, $member['photo'] ?? '') }}">
                            </div>
                            <div class="form-group mt-2 mb-0">
                                <label>Or upload photo</label>
                                <input type="file" name="team_photo_file[{{ $i }}]" class="form-control-file" accept="image/*">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Why trust us</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Eyebrow</label>
                        <input type="text" name="trust_eyebrow" class="form-control" value="{{ old('trust_eyebrow', $trust['eyebrow'] ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label>Section title</label>
                        <input type="text" name="trust_section_title" class="form-control" value="{{ old('trust_section_title', $trust['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <input type="text" name="trust_subtitle" class="form-control" value="{{ old('trust_subtitle', $trust['subtitle'] ?? '') }}">
                    </div>
                    @foreach($trustRows as $i => $item)
                        <div class="border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-md-0">
                                        <label>Reason {{ $i + 1 }}</label>
                                        <input type="text" name="trust_title[]" class="form-control" value="{{ old('trust_title.'.$i, $item['title']) }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-md-0">
                                        <label>Icon</label>
                                        <input type="text" name="trust_icon[]" class="form-control" value="{{ old('trust_icon.'.$i, $item['icon'] ?? 'ti-check') }}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group mb-0">
                                        <label>Description</label>
                                        <input type="text" name="trust_description[]" class="form-control" value="{{ old('trust_description.'.$i, $item['description'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title">Bottom CTA</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="cta_title" class="form-control" value="{{ old('cta_title', $cta['title']) }}">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <input type="text" name="cta_subtitle" class="form-control" value="{{ old('cta_subtitle', $cta['subtitle']) }}">
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Primary button label</label>
                                <input type="text" name="cta_button_label" class="form-control" value="{{ old('cta_button_label', $cta['button_label']) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Primary button URL</label>
                                <input type="text" name="cta_button_url" class="form-control" value="{{ old('cta_button_url', $cta['button_url']) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>WhatsApp button label</label>
                                <input type="text" name="cta_secondary_label" class="form-control" value="{{ old('cta_secondary_label', $cta['secondary_label'] ?? 'Chat on WhatsApp') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5">
                <button type="submit" class="btn btn-primary">Save About Content</button>
            </div>
        </form>
    </div>
</div>
@endsection
