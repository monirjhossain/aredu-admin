@extends('layouts.public-application')

@section('title', $title)

@php
    $studentName = old('student_name');
    $selectedUniversityId = old('university_id');
    $selectedSubjectId = old('subject_id');
@endphp

@section('content')
<div class="hero">
    <div class="logo-wrap">
        <img src="{{ $logoUrl }}" alt="AR Education Logo">
    </div>
    <h1>{{ $title }}</h1>
    <p>Complete your application in a few simple steps. Our counselors will contact you shortly.</p>
    @if (!empty($branch))
        <div class="branch-badge">{{ $branch->name }} Branch</div>
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

        <form action="{{ route('application-form.store', ['branch' => $branchId]) }}" method="POST" id="applicationForm" novalidate>
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

                <div class="wizard-actions">
                    <button type="button" class="btn btn-back" data-action="prev">Back</button>
                    <button type="button" class="btn btn-next" data-action="next">Next Step</button>
                </div>
            </div>

            {{-- Step 3: University --}}
            <div class="step-panel" data-step="3">
                <h2 class="step-heading">University & Subject</h2>
                <p class="step-subtitle">Choose where and what you want to study. You can skip if undecided.</p>

                <div class="field-grid">
                    <div class="form-group" data-field="university_id">
                        <label for="university_id">University</label>
                        <select name="university_id" id="university_id" class="form-control @error('university_id') server-invalid @enderror">
                            <option value="">Select university</option>
                            @foreach ($universities as $university)
                                <option value="{{ $university->id }}" {{ (string) $selectedUniversityId === (string) $university->id ? 'selected' : '' }}>
                                    {{ $university->post_title }}
                                </option>
                            @endforeach
                        </select>
                        @error('university_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group" data-field="subject_id">
                        <label for="subject_id">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-control @error('subject_id') server-invalid @enderror">
                            <option value="">Select subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ (string) $selectedSubjectId === (string) $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
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
                    <div class="review-row"><span>Preferred Country</span><span data-review="country_preferred">—</span></div>
                    <div class="review-row"><span>Preferred Course</span><span data-review="course">—</span></div>
                    <div class="review-row"><span>University</span><span data-review="university_id">—</span></div>
                    <div class="review-row"><span>Subject</span><span data-review="subject_id">—</span></div>
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
@include('shared.inquiry-subject-script')
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
        2: ['last_education'],
        3: [],
        4: []
    };

    const fieldStepMap = {
        student_name: 1, email: 1, mobile_no: 1, country: 1,
        last_education: 2, country_preferred: 2, course: 2,
        university_id: 3, subject_id: 3,
        message: 4
    };

    function getField(name) {
        return form.querySelector('[name="' + name + '"]');
    }

    function getFieldLabel(name) {
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
        const value = field.value.trim();

        if (requiredByStep[1].includes(name) || requiredByStep[2].includes(name)) {
            if (!value) valid = false;
        }

        if (name === 'email' && value) {
            valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
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
        ['student_name', 'email', 'mobile_no', 'country', 'last_education', 'country_preferred', 'course', 'university_id', 'subject_id'].forEach(function (name) {
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

    ['student_name', 'email', 'mobile_no', 'last_education'].forEach(function (name) {
        const field = getField(name);
        if (field) {
            field.addEventListener('input', function () { validateField(name); });
            field.addEventListener('blur', function () { validateField(name); });
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
