<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfficeStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $student = $this->route('student');

        return $student && $this->user() && $this->user()->can('updateOfficeStatus', $student);
    }

    public function rules(): array
    {
        return [
            'status_id' => ['required', 'integer', 'exists:student_statuses,id'],
        ];
    }
}
