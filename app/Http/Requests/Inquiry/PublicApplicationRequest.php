<?php

namespace App\Http\Requests\Inquiry;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublicApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_no' => 'required|string|max:50',
            'country' => 'nullable|string|max:255',
            'country_preferred' => 'nullable|string|max:255',
            'last_education' => 'required|string|max:255',
            'course' => 'nullable|string|max:255',
            'university_ids' => 'nullable|array',
            'university_ids.*' => [
                'integer',
                Rule::exists('posts', 'id')->where(function ($q) {
                    $q->where('post_type', 'university');
                }),
            ],
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'integer|exists:subjects,id',
            'message' => 'nullable|string|max:5000',
            'branch_id' => 'required|integer|exists:branches,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'student_name' => 'student name',
            'mobile_no' => 'phone number',
            'country_preferred' => 'country preferred',
            'last_education' => 'last education',
            'course' => 'preferred course',
            'university_ids' => 'university',
            'university_ids.*' => 'university',
            'subject_ids' => 'subject',
            'subject_ids.*' => 'subject',
        ];
    }
}
