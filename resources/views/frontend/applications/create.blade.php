@extends('layouts.public-application')

@section('title', $title)

@php
    $studentName = old('student_name');
    $storeUrl = $storeUrl ?? route('application-form.store', ['branch' => $branchId]);
    $subtitle = $subtitle ?? 'Complete your application in a few simple steps. Our counselors will contact you shortly.';
    $showCscaField = !empty($showCscaField);
    $cscaExams = $cscaExams ?? collect();
    $selectedCscaIds = array_map('intval', (array) old('csca_subject_ids', []));
@endphp

@section('content')
<div class="hero">
    <div class="logo-wrap">
        <img src="{{ $logoUrl }}" alt="AR Education Logo">
    </div>
    <h1>{{ $title }}</h1>
    <p>{{ $subtitle }}</p>
    @if (!empty($branch))
        <div class="branch-badge">{{ $branch->name }} Branch @if (!empty($typeLabel)) · {{ $typeLabel }} @endif</div>
    @endif
</div>

<div class="wizard-card">
    <div class="wizard-progress">
        <div class="progress-track">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="steps-row">
            <div class="step-item active" data-step-indicator="1">
                <div class="step-circle">1</div>
                <span class="step-label">Personal</span>
            </div>
            <div class="step-item" data-step-indicator="2">
                <div class="step-circle">2</div>
                <span class="step-label">Study Plan</span>
            </div>
            <div class="step-item" data-step-indicator="3">
                <div class="step-circle">3</div>
                <span class="step-label">University</span>
            </div>
            <div class="step-item" data-step-indicator="4">
                <div class="step-circle">4</div>
                <span class="step-label">Review</span>
            </div>
        </div>
    </div>

    <div class="wizard-body">
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $storeUrl }}" method="POST" id="applicationForm" novalidate>
            @csrf
            <input type="hidden" name="branch_id" value="{{ $branchId }}">

            {{-- Step 1: Personal --}}
            <div class="step-panel active" data-step="1">
                <h2 class="step-heading">Personal Information</h2>
                <p class="step-subtitle">Tell us how we can reach you.</p>

                <div class="field-grid">
                    <div class="form-group" data-field="student_name">
                        <label for="student_name" class="required">Student Name</label>
                        <input type="text" name="student_name" id="student_name" value="{{ $studentName }}"
                            class="form-control @error('student_name') server-invalid @enderror @error('first_name') server-invalid @enderror"
                            placeholder="Enter your full name" required>
                        <span class="field-error">Please enter your full name.</span>
                        @error('student_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        @error('first_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" data-field="email">
                        <label for="email" class="required">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="form-control @error('email') server-invalid @enderror"
                            placeholder="example@email.com" required>
                        <span class="field-error">Please enter a valid email address.</span>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" data-field="mobile_no">
                        <label for="mobile_no" class="required">Phone Number</label>
                        <input type="tel" name="mobile_no" id="mobile_no" value="{{ old('mobile_no') }}"
                            class="form-control @error('mobile_no') server-invalid @enderror"
                            placeholder="01XXXXXXXXX" required>
                        <span class="field-error">Please enter your phone number.</span>
                        @error('mobile_no')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" data-field="country">
                        <label for="country">Your Country</label>
                        <select name="country" id="country" class="form-control @error('country') server-invalid @enderror">
                            <option value="">Select country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country }}" {{ old('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                        @error('country')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="wizard-actions">
                    <button type="button" class="btn btn-next" data-action="next">Next Step</button>
                </div>
            </div>

            {{-- Step 2: Study Plan --}}
            <div class="step-panel" data-step="2">
                <h2 class="step-heading">Study Preferences</h2>
                <p class="step-subtitle">Share your education background and study goals.</p>

                <div class="field-grid">
                    <div class="form-group" data-field="last_education">
                        <label for="last_education" class="required">Last Education</label>
                        <select name="last_education" id="last_education" class="form-control @error('last_education') server-invalid @enderror" required>
                            <option value="">Select last education</option>
                            @foreach ($lastEducationLevels as $level)
                                <option value="{{ $level }}" {{ old('last_education') == $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                        <span class="field-error">Please select your last education level.</span>
                        @error('last_education')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" data-field="result">
                        <label for="result" class="required">Last Result</label>
                        <input type="text" name="result" id="result" value="{{ old('result') }}"
                            class="form-control @error('result') server-invalid @enderror"
                            placeholder="GPA / Division" required>
                        <span class="field-error">Please enter your last result.</span>
                        @error('result')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" data-field="country_preferred">
                        <label for="country_preferred">Preferred Country</label>
                        <select name="country_preferred" id="country_preferred" class="form-control @error('country_preferred') server-invalid @enderror">
                            <option value="">Select preferred country</option>
                            @foreach ($preferredCountries as $country)
                                <option value="{{ $country }}" {{ old('country_preferred') == $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                        @error('country_preferred')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" data-field="course">
                        <label for="course">Preferred Course</label>
                        <select name="course" id="course" class="form-control @error('course') server-invalid @enderror">
                            <option value="">Select preferred course</option>
                            @foreach ($courses as $courseItem)
                                <option value="{{ $courseItem->name }}" {{ old('course') == $courseItem->name ? 'selected' : '' }}>{{ $courseItem->name }}</option>
                            @endforeach
                        </select>
                        @error('course')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                @if ($showCscaField)
                    <div class="form-group" data-field="csca_subject_ids">
                        <div class="ms-label-row">
                            <label for="csca_subject_ids" class="required">CSCA Subjects</label>
                            <span class="ms-count" data-ms-count="csca_subject_ids">0 selected</span>
                        </div>
                        <div class="ms-wrap" data-multi-select data-ms-id="csca_subject_ids">
                            <div class="ms-box @error('csca_subject_ids') is-invalid @enderror @error('csca_subject_ids.*') is-invalid @enderror">
                                <div class="ms-chips"></div>
                                <input type="text" class="ms-search" placeholder="Search and select CSCA subjects..." autocomplete="off">
                            </div>
                            <div class="ms-dropdown"></div>
                            <select name="csca_subject_ids[]" id="csca_subject_ids" class="ms-native" multiple>
                                @foreach ($cscaExams as $exam)
                                    <option value="{{ $exam->id }}" {{ in_array((int) $exam->id, $selectedCscaIds, true) ? 'selected' : '' }}>
                                        {{ $exam->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="ms-hint">You can select multiple CSCA subjects</div>
                        </div>
                        <span class="field-error">Please select at least one CSCA subject.</span>
                        @error('csca_subject_ids')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        @error('csca_subject_ids.*')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                @endif

                <div class="wizard-actions">
                    <button type="button" class="btn btn-back" data-action="prev">Back</button>
                    <button type="button" class="btn btn-next" data-action="next">Next Step</button>
                </div>
            </div>

            {{-- Step 3: University & Subject (multiple select) --}}
            <div class="step-panel" data-step="3">
                <h2 class="step-heading">University & Subject</h2>
                <p class="step-subtitle">Select one or more universities and subjects. Search, tap to add, and remove with ×.</p>

                <div class="field-grid">
                    @include('shared.inquiry-university-subject-fields', [
                        'columnClass' => 'form-group',
                        'groupClass' => null,
                        'multiSelectTheme' => 'chip',
                        'includeMultiSelectScript' => false,
                        'withDataField' => true,
                    ])
                </div>

                <div class="wizard-actions">
                    <button type="button" class="btn btn-back" data-action="prev">Back</button>
                    <button type="button" class="btn btn-next" data-action="next">Next Step</button>
                </div>
            </div>

            {{-- Step 4: Review --}}
            <div class="step-panel" data-step="4">
                <h2 class="step-heading">Review & Submit</h2>
                <p class="step-subtitle">Check your details and add any message before submitting.</p>

                <div class="review-box" id="reviewSummary">
                    <h3>Application Summary</h3>
                    <div class="review-row"><span>Name</span><span data-review="student_name">—</span></div>
                    <div class="review-row"><span>Email</span><span data-review="email">—</span></div>
                    <div class="review-row"><span>Phone</span><span data-review="mobile_no">—</span></div>
                    <div class="review-row"><span>Country</span><span data-review="country">—</span></div>
                    <div class="review-row"><span>Last Education</span><span data-review="last_education">—</span></div>
                    <div class="review-row"><span>Last Result</span><span data-review="result">—</span></div>
                    <div class="review-row"><span>Preferred Country</span><span data-review="country_preferred">—</span></div>
                    <div class="review-row"><span>Preferred Course</span><span data-review="course">—</span></div>
                    <div class="review-row"><span>University</span><span data-review="university_ids">—</span></div>
                    <div class="review-row"><span>Subject</span><span data-review="subject_ids">—</span></div>
                    @if ($showCscaField)
                        <div class="review-row"><span>CSCA Subjects</span><span data-review="csca_subject_ids">—</span></div>
                    @endif
                </div>

                <div class="form-group" data-field="message">
                    <label for="message">Message <span style="color:#9ca3af;font-weight:500;">(optional)</span></label>
                    <textarea name="message" id="message" class="form-control @error('message') server-invalid @enderror"
                        placeholder="Tell us about your study plans, preferred intake, or any questions...">{{ old('message') }}</textarea>
                    @error('message')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="wizard-actions">
                    <button type="button" class="btn btn-back" data-action="prev">Back</button>
                    <button type="submit" class="btn submit-btn" id="submitBtn">Submit Application</button>
                </div>
            </div>
        </form>
    </div>
</div>

<p class="footer-note">
    Powered by <a href="https://areducation.net/" target="_blank" rel="noopener">AR Education</a>
</p>
@endsection

@push('scripts')
@include('shared.inquiry-multi-select-script', ['multiSelectTheme' => 'chip'])
<script>
(function () {
    const form = document.getElementById('applicationForm');
    if (!form) return;

    const panels = Array.from(form.querySelectorAll('.step-panel'));
    const indicators = Array.from(document.querySelectorAll('[data-step-indicator]'));
    const progressFill = document.getElementById('progressFill');
    const submitBtn = document.getElementById('submitBtn');
    const totalSteps = panels.length;
    let currentStep = 1;

    const requiredByStep = {
        1: ['student_name', 'email', 'mobile_no'],
        2: ['last_education', 'result' @if ($showCscaField), 'csca_subject_ids' @endif],
        3: [],
        4: []
    };

    const fieldStepMap = {
        student_name: 1, email: 1, mobile_no: 1, country: 1,
        last_education: 2, result: 2, country_preferred: 2, course: 2,
        csca_subject_ids: 2, 'csca_subject_ids[]': 2,
        university_ids: 3, subject_ids: 3,
        'university_ids[]': 3, 'subject_ids[]': 3,
        message: 4
    };

    function getMultiSelectLabel(name) {
        const select = document.getElementById(name);
        if (!select) return '—';
        const labels = Array.from(select.selectedOptions).map(function (option) {
            return option.text;
        }).filter(Boolean);
        return labels.length ? labels.join(', ') : '—';
    }

    function getField(name) {
        return form.querySelector('[name="' + name + '"]') || document.getElementById(name);
    }

    function getFieldLabel(name) {
        if (name === 'university_ids' || name === 'subject_ids' || name === 'csca_subject_ids') {
            return getMultiSelectLabel(name);
        }

        const field = getField(name);
        if (!field) return name;
        if (field.tagName === 'SELECT') {
            return field.options[field.selectedIndex] ? field.options[field.selectedIndex].text : '—';
        }
        return field.value.trim() || '—';
    }

    function validateField(name) {
        const field = getField(name);
        const group = form.querySelector('[data-field="' + name + '"]');
        if (!field || !group) return true;

        let valid = true;

        if (field.multiple) {
            valid = Array.from(field.selectedOptions).some(function (option) {
                return option.value;
            });
        } else {
            const value = field.value.trim();

            if (requiredByStep[1].includes(name) || requiredByStep[2].includes(name)) {
                if (!value) valid = false;
            }

            if (name === 'email' && value) {
                valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            }
        }

        group.classList.toggle('has-error', !valid);
        field.classList.toggle('is-invalid', !valid);

        return valid;
    }

    function validateStep(step) {
        const fields = requiredByStep[step] || [];
        let valid = true;
        fields.forEach(function (name) {
            if (!validateField(name)) valid = false;
        });
        return valid;
    }

    function updateReview() {
        ['student_name', 'email', 'mobile_no', 'country', 'last_education', 'result', 'country_preferred', 'course', 'university_ids', 'subject_ids', 'csca_subject_ids'].forEach(function (name) {
            const target = document.querySelector('[data-review="' + name + '"]');
            if (target) target.textContent = getFieldLabel(name);
        });
    }

    function goToStep(step) {
        currentStep = Math.max(1, Math.min(totalSteps, step));

        panels.forEach(function (panel) {
            panel.classList.toggle('active', Number(panel.dataset.step) === currentStep);
        });

        indicators.forEach(function (item) {
            const stepNum = Number(item.dataset.stepIndicator);
            item.classList.remove('active', 'completed');
            if (stepNum === currentStep) item.classList.add('active');
            if (stepNum < currentStep) item.classList.add('completed');
        });

        if (progressFill) {
            progressFill.style.width = ((currentStep / totalSteps) * 100) + '%';
        }

        if (currentStep === totalSteps) updateReview();
        if ((currentStep === 2 || currentStep === 3) && typeof window.refreshApplicationMultiSelect === 'function') {
            window.refreshApplicationMultiSelect();
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    form.querySelectorAll('[data-action="next"]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (validateStep(currentStep)) goToStep(currentStep + 1);
        });
    });

    form.querySelectorAll('[data-action="prev"]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            goToStep(currentStep - 1);
        });
    });

    form.addEventListener('submit', function (e) {
        if (!validateStep(1) || !validateStep(2)) {
            e.preventDefault();
            goToStep(!validateStep(1) ? 1 : 2);
            return;
        }
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
        }
    });

    ['student_name', 'email', 'mobile_no', 'last_education', 'result'].forEach(function (name) {
        const field = getField(name);
        if (field) {
            field.addEventListener('input', function () { validateField(name); });
            field.addEventListener('blur', function () { validateField(name); });
        }
    });

    ['university_ids', 'subject_ids', 'csca_subject_ids'].forEach(function (name) {
        const select = document.getElementById(name);
        if (select) {
            select.addEventListener('change', function () {
                if (currentStep === totalSteps) updateReview();
            });
        }
    });

    @if ($errors->any())
        (function () {
            const errorFields = @json(array_keys($errors->toArray()));
            let targetStep = 1;
            errorFields.forEach(function (name) {
                if (fieldStepMap[name] && fieldStepMap[name] > targetStep) {
                    targetStep = fieldStepMap[name];
                }
            });
            goToStep(targetStep);
        })();
    @else
        goToStep(1);
    @endif
})();
</script>
@endpush
