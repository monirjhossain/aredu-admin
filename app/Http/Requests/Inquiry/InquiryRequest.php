<?php

namespace App\Http\Requests\Inquiry;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class InquiryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_name' => 'required|string|max:255',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'country' => 'nullable|string|max:255',
            'country_preferred' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'last_education' => 'required|string|max:255',
            'message' => 'nullable|string|max:5000',
            'mobile_no' => 'required|string|max:50',
            'type' => 'nullable|string|max:255',
            'university_ids' => 'nullable|array',
            'university_ids.*' => [
                'integer',
                Rule::exists('posts', 'id')->where(function ($q) {
                    $q->where('post_type', 'university');
                }),
            ],
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'integer|exists:subjects,id',
        ];
    }

    public function attributes()
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

    public function messages()
    {
        return [];
    }
}
