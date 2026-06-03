<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttachStudentUniversityRequest extends FormRequest
{
    public function authorize(): bool
    {
        $student = $this->route('student');

        return $student && $this->user() && $this->user()->can('updatePreferences', $student);
    }

    public function rules(): array
    {
        return [
            'university_id' => [
                'required',
                'integer',
                Rule::exists('posts', 'id')->where(fn ($q) => $q->where('post_type', 'university')),
            ],
        ];
    }
}
