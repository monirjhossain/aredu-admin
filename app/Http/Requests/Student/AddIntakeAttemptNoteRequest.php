<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class AddIntakeAttemptNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $student = $this->route('student');

        return $student && $this->user() && $this->user()->can('manage', $student);
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:2000'],
            'attempt_id' => ['required', 'integer', 'exists:student_intake_attempts,id'],
        ];
    }
}
