<?php

namespace App\Http\Requests\Post;

// use Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'post_title' =>  'required',
            'post_content' => 'required',
            // 'file' => 'required',
            // 'categories' =>  'required',
            // 'tags' =>  'required',
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
            'address' => 'Address',
            'city' =>  'City',
            'condominium_name' =>  'Condominium Name',
            'country' =>  'Country',
            'fiscal_code' =>  'Fiscal Code',
            'iban' =>  'IBAN',
            'pec' =>  'PEC',
            'sdi' =>  'SDI',
            'zip_code' =>  'Zip Code',
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
