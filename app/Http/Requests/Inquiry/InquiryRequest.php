<?php

namespace App\Http\Requests\Inquiry;

// use Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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
            'university_id' => [
                'nullable',
                'integer',
                Rule::exists('posts', 'id')->where(function ($q) {
                    $q->where('post_type', 'university');
                }),
            ],
            'subject_id' => 'nullable|integer|exists:subjects,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'student_name' => 'student name',
            'mobile_no' => 'phone number',
            'country_preferred' => 'country preferred',
            'last_education' => 'last education',
            'course' => 'preferred course',
            'university_id' => 'university',
            'subject_id' => 'subject',
            // 'address' => 'Address',
            // 'city' =>  'City',
            // 'condominium_name' =>  'Condominium Name',
            // 'country' =>  'Country',
            // 'fiscal_code' =>  'Fiscal Code',
            // 'iban' =>  'IBAN',
            // 'pec' =>  'PEC',
            // 'sdi' =>  'SDI',
            // 'zip_code' =>  'Zip Code',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // 'video_url.different' => __('the_main_video_and_demo_video_must_be_different'),
            // 'video_url.regex' => __('support_only_vimeo_url'),
            // 'lesson.demo.regex' => __('support_only_vimeo_url'),
        ];
    }
}
