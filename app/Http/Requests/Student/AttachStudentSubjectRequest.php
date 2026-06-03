<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class AttachStudentSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        $student = $this->route('student');

        return $student && $this->user() && $this->user()->can('updatePreferences', $student);
    }

    public function rules(): array
    {
        return [
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
        ];
    }
}
