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
        $rules = [
            'country' => 'required',
            'country_preferred' => 'required',
            'course' => 'required',
            'email' => 'required',
            'first_name' => 'required',
            'last_education' => 'required',
            'last_name' => 'required',
            'message' => 'required',
            'mobile_no' => 'required',
            // 'oppurtunity' => 'required',
        ];
        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
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
