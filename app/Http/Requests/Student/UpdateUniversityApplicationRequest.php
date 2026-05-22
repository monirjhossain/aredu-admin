<?php

namespace App\Http\Requests\Student;

use App\Models\Student\StudentUniversity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateUniversityApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var StudentUniversity|null $application */
        $application = $this->route('application');

        if (!$application || !$this->user()) {
            return false;
        }

        $application->loadMissing('student');

        return $application->student
            && $this->user()->can('updateApplicationStatus', $application->student);
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'status_id' => ['required', 'integer', 'exists:university_statuses,id'],
            'application_no' => ['nullable', 'string', 'max:255'],
            'portal_type' => ['required', 'in:0,1'],
            'login_id' => ['nullable', 'string', 'max:255'],
            'login_password' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
            'admission_notice' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'jw_copy' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'visa_copy' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var StudentUniversity|null $application */
            $application = $this->route('application');

            if (!$application) {
                return;
            }

            if ((int) $this->input('student_id') !== (int) $application->student_id) {
                $validator->errors()->add('student_id', 'Student does not match this application.');
            }
        });
    }
}
