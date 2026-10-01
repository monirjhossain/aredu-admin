<?php

namespace App\Http\Requests\Student;

use App\Models\Student\StudentIntakeAttempt;
use App\Support\FileStudentIdAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarryOverIntakeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $student = $this->route('student');

        return $student && $this->user() && $this->user()->can('manage', $student);
    }

    protected function prepareForValidation(): void
    {
        if (!FileStudentIdAccess::canEdit($this->user())) {
            $this->offsetUnset('new_file_student_id');
        }
    }

    public function rules(): array
    {
        $outcomes = array_keys(StudentIntakeAttempt::outcomeLabels());
        $outcomes = array_values(array_filter(
            $outcomes,
            static fn ($o) => $o !== StudentIntakeAttempt::OUTCOME_IN_PROGRESS
        ));
        $studentId = optional($this->route('student'))->id;

        return [
            'new_intake' => ['required', 'string', 'max:100'],
            'outcome' => ['nullable', 'string', Rule::in($outcomes)],
            'note' => ['nullable', 'string', 'max:2000'],
            'start_status_id' => ['nullable', 'integer', 'exists:student_statuses,id'],
            'new_file_student_id' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('students', 'file_student_id')->ignore($studentId),
            ],
        ];
    }
}
