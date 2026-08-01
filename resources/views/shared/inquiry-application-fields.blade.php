@php
    $fieldPrefix = $fieldPrefix ?? '';
    $studentName = old('student_name');

    if ($studentName === null && isset($inquiry)) {
        $studentName = trim(($inquiry->first_name ?? '') . ' ' . ($inquiry->last_name ?? ''));
    }

    $selectedBranchId = old('branch_id', $inquiry->branch_id ?? ($branchId ?? null));
    $inputClass = ($compact ?? false) ? 'form-control form-control-sm' : 'form-control';
    $groupClass = array_key_exists('groupClass', get_defined_vars()) && $groupClass === null
        ? null
        : ($groupClass ?? 'form-group');
    $columnClass = $columnClass ?? 'col-lg-6 col-md-6 col-sm-12';
    $messageColumnClass = $messageColumnClass ?? 'col-lg-12 col-md-12 col-sm-12';
@endphp

<div class="{{ $wrapperClass ?? 'row' }}">
    @if (!empty($showBranchField) && isset($branches) && $branches->isNotEmpty())
        <div class="{{ $columnClass }}">
            @if ($groupClass)<div class="{{ $groupClass }}">@endif
                <label for="branch_id">Branch</label>
                <select name="branch_id" id="branch_id" class="{{ $inputClass }} @error('branch_id') is-invalid @enderror">
                    <option value="">— Select branch —</option>
                    @foreach ($branches as $branchOption)
                        <option value="{{ $branchOption->id }}" {{ (string) $selectedBranchId === (string) $branchOption->id ? 'selected' : '' }}>
                            {{ $branchOption->name }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            @if ($groupClass)</div>@endif
        </div>
    @endif

    <div class="{{ $columnClass }}">
        @if ($groupClass)<div class="{{ $groupClass }}">@endif
            <label for="student_name" class="{{ ($requireCore ?? false) ? 'required' : '' }}">Student Name</label>
            <input type="text" name="student_name" id="student_name"
                value="{{ $studentName }}"
                class="{{ $inputClass }} @error('student_name') is-invalid @enderror @error('first_name') is-invalid @enderror"
                placeholder="Enter full name" {{ ($requireCore ?? false) ? 'required' : '' }}>
            @error('student_name')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
            @error('first_name')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        @if ($groupClass)</div>@endif
    </div>

    <div class="{{ $columnClass }}">
        @if ($groupClass)<div class="{{ $groupClass }}">@endif
            <label for="email" class="{{ ($requireCore ?? false) ? 'required' : '' }}">Email</label>
            <input type="email" name="email" id="email"
                value="{{ old('email', $inquiry->email ?? '') }}"
                class="{{ $inputClass }} @error('email') is-invalid @enderror"
                placeholder="example@email.com" {{ ($requireCore ?? false) ? 'required' : '' }}>
            @error('email')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        @if ($groupClass)</div>@endif
    </div>

    <div class="{{ $columnClass }}">
        @if ($groupClass)<div class="{{ $groupClass }}">@endif
            <label for="mobile_no" class="{{ ($requireCore ?? false) ? 'required' : '' }}">Phone Number</label>
            <input type="text" name="mobile_no" id="mobile_no"
                value="{{ old('mobile_no', $inquiry->mobile_no ?? '') }}"
                class="{{ $inputClass }} @error('mobile_no') is-invalid @enderror"
                placeholder="01XXXXXXXXX" {{ ($requireCore ?? false) ? 'required' : '' }}>
            @error('mobile_no')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        @if ($groupClass)</div>@endif
    </div>

    <div class="{{ $columnClass }}">
        @if ($groupClass)<div class="{{ $groupClass }}">@endif
            <label for="country">Country</label>
            <select name="country" id="country" class="{{ $inputClass }} @error('country') is-invalid @enderror">
                <option value="">— Select country —</option>
                @foreach ($countries as $country)
                    <option value="{{ $country }}" {{ old('country', $inquiry->country ?? '') == $country ? 'selected' : '' }}>
                        {{ $country }}
                    </option>
                @endforeach
            </select>
            @error('country')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        @if ($groupClass)</div>@endif
    </div>

    <div class="{{ $columnClass }}">
        @if ($groupClass)<div class="{{ $groupClass }}">@endif
            <label for="country_preferred">Country Preferred</label>
            <select name="country_preferred" id="country_preferred" class="{{ $inputClass }} @error('country_preferred') is-invalid @enderror">
                <option value="">— Select preferred country —</option>
                @foreach ($preferredCountries as $country)
                    <option value="{{ $country }}" {{ old('country_preferred', $inquiry->country_preferred ?? '') == $country ? 'selected' : '' }}>
                        {{ $country }}
                    </option>
                @endforeach
            </select>
            @error('country_preferred')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        @if ($groupClass)</div>@endif
    </div>

    <div class="{{ $columnClass }}">
        @if ($groupClass)<div class="{{ $groupClass }}">@endif
            <label for="last_education" class="{{ ($requireCore ?? false) ? 'required' : '' }}">Last Education</label>
            <select name="last_education" id="last_education" class="{{ $inputClass }} @error('last_education') is-invalid @enderror" {{ ($requireCore ?? false) ? 'required' : '' }}>
                <option value="">— Select last education —</option>
                @foreach ($lastEducationLevels as $level)
                    <option value="{{ $level }}" {{ old('last_education', $inquiry->last_education ?? '') == $level ? 'selected' : '' }}>
                        {{ $level }}
                    </option>
                @endforeach
            </select>
            @error('last_education')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        @if ($groupClass)</div>@endif
    </div>

    <div class="{{ $columnClass }}">
        @if ($groupClass)<div class="{{ $groupClass }}">@endif
            <label for="course">Preferred Course</label>
            <select name="course" id="course" class="{{ $inputClass }} @error('course') is-invalid @enderror">
                <option value="">— Select preferred course —</option>
                @foreach ($courses as $courseItem)
                    <option value="{{ $courseItem->name }}" {{ old('course', $inquiry->course ?? '') == $courseItem->name ? 'selected' : '' }}>
                        {{ $courseItem->name }}
                    </option>
                @endforeach
            </select>
            @error('course')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        @if ($groupClass)</div>@endif
    </div>

    @include('shared.inquiry-university-subject-fields', [
        'columnClass' => $columnClass,
        'groupClass' => $groupClass ?: null,
        'multiSelectTheme' => $multiSelectTheme ?? 'select2',
        'includeMultiSelectScript' => $includeSubjectScript ?? true,
        'inquiry' => $inquiry ?? null,
    ])

    <div class="{{ $messageColumnClass }}">
        @if ($groupClass)<div class="{{ $groupClass }}">@endif
            <label for="message">Message</label>
            <textarea name="message" id="message" rows="3"
                class="{{ $inputClass }} @error('message') is-invalid @enderror"
                placeholder="Tell us about study plans...">{{ old('message', $inquiry->message ?? '') }}</textarea>
            @error('message')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        @if ($groupClass)</div>@endif
    </div>
</div>
