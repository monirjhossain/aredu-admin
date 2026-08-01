@once
<style>
    .ms-wrap { position: relative; }
    .ms-box {
        border: 1.5px solid #e5e7eb;
        border-radius: 14px;
        background: #fafafa;
        padding: 10px 12px;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .ms-box:focus-within {
        border-color: #f97316;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.12);
    }
    .ms-box.is-invalid { border-color: #fca5a5; background: #fffafa; }
    .ms-chips { display: flex; flex-wrap: wrap; gap: 8px; min-height: 0; margin-bottom: 8px; }
    .ms-chips:empty { display: none; margin-bottom: 0; }
    .ms-chip {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px;
        border-radius: 999px; background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
        color: #9a3412; font-size: 0.82rem; font-weight: 700; line-height: 1.2;
    }
    .ms-chip button {
        border: none; background: rgba(154, 52, 18, 0.12); color: #9a3412;
        width: 18px; height: 18px; border-radius: 50%; cursor: pointer; font-size: 0.9rem; line-height: 1; padding: 0;
    }
    .ms-search {
        width: 100%; border: none; background: transparent; font: inherit;
        font-size: 0.92rem; padding: 4px 2px; outline: none; color: #111827;
    }
    .ms-search::placeholder { color: #9ca3af; }
    .ms-dropdown {
        display: none; position: absolute; left: 0; right: 0; top: calc(100% + 6px);
        max-height: 220px; overflow-y: auto; background: #fff; border: 1px solid #e5e7eb;
        border-radius: 14px; box-shadow: 0 18px 40px rgba(17, 24, 39, 0.12); z-index: 30;
    }
    .ms-dropdown.open { display: block; }
    .ms-option {
        padding: 11px 14px; cursor: pointer; font-size: 0.9rem; font-weight: 600;
        color: #374151; border-bottom: 1px solid #f3f4f6;
    }
    .ms-option:last-child { border-bottom: none; }
    .ms-option:hover, .ms-option.focused { background: #fff7ed; color: #c2410c; }
    .ms-empty { padding: 14px; text-align: center; color: #9ca3af; font-size: 0.86rem; }
    .ms-native { display: none !important; }
    .ms-hint { margin-top: 6px; font-size: 0.78rem; color: #9ca3af; }
    .inquiry-ms-select2 + .select2-container { width: 100% !important; }
    .inquiry-ms-select2 + .select2-container .select2-selection--multiple {
        min-height: 46px; border: 1.5px solid #e5e7eb; border-radius: 14px; padding: 4px 8px;
    }
    .inquiry-ms-select2 + .select2-container--focus .select2-selection--multiple {
        border-color: #f97316; box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.12);
    }
</style>
@endonce

@php
    $selectionResolver = app(\App\Services\InquirySelectionResolver::class);
    $selectedUniversityIds = $selectionResolver->selectedUniversityIds($inquiry ?? null, $universities);
    $selectedSubjectIds = $selectionResolver->selectedSubjectIds($inquiry ?? null, $subjects);
    $multiSelectTheme = $multiSelectTheme ?? 'chip';
@endphp

<div class="{{ $columnClass ?? 'col-lg-6 col-md-6 col-sm-12' }}" @if(!empty($withDataField)) data-field="university_ids" @endif>
    @if ($groupClass ?? false)<div class="{{ $groupClass }}">@endif
        <div class="ms-label-row">
            <label for="university_ids">{{ $universityLabel ?? 'University' }}</label>
            <span class="ms-count" data-ms-count="university_ids">0 selected</span>
        </div>

        @if ($multiSelectTheme === 'select2')
            <select name="university_ids[]" id="university_ids" class="form-control inquiry-ms-select2 @error('university_ids') is-invalid @enderror @error('university_ids.*') is-invalid @enderror" multiple data-placeholder="Select universities">
                @foreach ($universities as $university)
                    <option value="{{ $university->id }}" {{ in_array($university->id, $selectedUniversityIds, true) ? 'selected' : '' }}>
                        {{ $university->post_title }}
                    </option>
                @endforeach
            </select>
        @else
            <div class="ms-wrap" data-multi-select data-ms-id="university_ids" data-ms-trigger="subjects">
                <div class="ms-box @error('university_ids') is-invalid @enderror @error('university_ids.*') is-invalid @enderror">
                    <div class="ms-chips"></div>
                    <input type="text" class="ms-search" placeholder="Search and select universities..." autocomplete="off">
                </div>
                <div class="ms-dropdown"></div>
                <select name="university_ids[]" id="university_ids" class="ms-native" multiple>
                    @foreach ($universities as $university)
                        <option value="{{ $university->id }}" {{ in_array($university->id, $selectedUniversityIds, true) ? 'selected' : '' }}>
                            {{ $university->post_title }}
                        </option>
                    @endforeach
                </select>
                <div class="ms-hint">You can select multiple universities</div>
            </div>
        @endif

        @error('university_ids')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        @error('university_ids.*')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    @if ($groupClass ?? false)</div>@endif
</div>

<div class="{{ $columnClass ?? 'col-lg-6 col-md-6 col-sm-12' }}" @if(!empty($withDataField)) data-field="subject_ids" @endif>
    @if ($groupClass ?? false)<div class="{{ $groupClass }}">@endif
        <div class="ms-label-row">
            <label for="subject_ids">{{ $subjectLabel ?? 'Subject' }}</label>
            <span class="ms-count" data-ms-count="subject_ids">0 selected</span>
        </div>

        @if ($multiSelectTheme === 'select2')
            <select name="subject_ids[]" id="subject_ids" class="form-control inquiry-ms-select2 @error('subject_ids') is-invalid @enderror @error('subject_ids.*') is-invalid @enderror" multiple data-placeholder="Select subjects">
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ in_array($subject->id, $selectedSubjectIds, true) ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
        @else
            <div class="ms-wrap" data-multi-select data-ms-id="subject_ids">
                <div class="ms-box @error('subject_ids') is-invalid @enderror @error('subject_ids.*') is-invalid @enderror">
                    <div class="ms-chips"></div>
                    <input type="text" class="ms-search" placeholder="Search and select subjects..." autocomplete="off">
                </div>
                <div class="ms-dropdown"></div>
                <select name="subject_ids[]" id="subject_ids" class="ms-native" multiple>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ in_array($subject->id, $selectedSubjectIds, true) ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                <div class="ms-hint">You can select multiple subjects</div>
            </div>
        @endif

        @error('subject_ids')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
        @error('subject_ids.*')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    @if ($groupClass ?? false)</div>@endif
</div>

@if ($includeMultiSelectScript ?? true)
    @include('shared.inquiry-multi-select-script', ['multiSelectTheme' => $multiSelectTheme])
@endif
