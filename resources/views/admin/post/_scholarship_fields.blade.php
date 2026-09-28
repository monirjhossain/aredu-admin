@php
    $scholarshipPost = $post ?? null;
    $scholarshipMeta = function ($key, $default = '') use ($scholarshipPost) {
        $old = old('meta.' . $key);
        if ($old !== null) {
            return $old;
        }
        return $scholarshipPost ? $scholarshipPost->getMeta($key, $default) : $default;
    };

    $cityOptions = collect($categories ?? [])->filter(function ($category) {
        return \App\Support\ChinaRegionMap::isLocationCategory($category->slug, $category->name);
    })->pluck('name')->unique()->sort()->values();

    $degreeOptions = \App\Models\ScholarshipFormOption::labels('degree');
    $languageOptions = \App\Models\ScholarshipFormOption::labels('language');
    $typeOptions = \App\Models\ScholarshipFormOption::labels('scholarship_type');
    $intakeOptions = \App\Models\ScholarshipFormOption::labels('intake');
    $coverageOptions = \App\Models\ScholarshipFormOption::labels('coverage');

    $select = function ($name, $options, $current) {
        $options = collect($options);
        if ($current && !$options->contains($current)) {
            $options->prepend($current);
        }
        return $options;
    };
@endphp

<div class="form-group">
    <label>Coverage</label>
    <select name="meta[coverage]" class="form-control">
        <option value="">Select coverage</option>
        @foreach($select('coverage', $coverageOptions, $scholarshipMeta('coverage')) as $option)
            <option value="{{ $option }}" @if($scholarshipMeta('coverage') == $option) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>City, Province</label>
    <select name="meta[city]" class="form-control">
        <option value="">Select city</option>
        @foreach($select('city', $cityOptions, $scholarshipMeta('city')) as $option)
            <option value="{{ $option }}" @if($scholarshipMeta('city') == $option) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Program ID</label>
    <input type="text" name="meta[program_id]" value="{{ $scholarshipMeta('program_id') }}" class="form-control" placeholder="CME2603300047">
</div>
<div class="form-group">
    <label>Degree</label>
    <select name="meta[degree]" class="form-control">
        <option value="">Select degree</option>
        @foreach($select('degree', $degreeOptions, $scholarshipMeta('degree')) as $option)
            <option value="{{ $option }}" @if($scholarshipMeta('degree') == $option) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Teaching language</label>
    <select name="meta[teaching_language]" class="form-control">
        <option value="">Select language</option>
        @foreach($select('language', $languageOptions, $scholarshipMeta('teaching_language')) as $option)
            <option value="{{ $option }}" @if($scholarshipMeta('teaching_language') == $option) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Intake</label>
    <select name="meta[intake]" class="form-control">
        <option value="">Select intake</option>
        @foreach($select('intake', $intakeOptions, $scholarshipMeta('intake')) as $option)
            <option value="{{ $option }}" @if($scholarshipMeta('intake') == $option) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Scholarship type</label>
    <select name="meta[scholarship_type]" class="form-control">
        <option value="">Select type</option>
        @foreach($select('type', $typeOptions, $scholarshipMeta('scholarship_type')) as $option)
            <option value="{{ $option }}" @if($scholarshipMeta('scholarship_type') == $option) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>Deadline</label>
    <input type="date" name="meta[deadline]" value="{{ $scholarshipMeta('deadline') }}" class="form-control">
</div>
