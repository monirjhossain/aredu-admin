<?php

namespace App\Http\Requests\Student;

// use Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            "study_id" =>  'required',
            "first_name" =>  'required',
            "last_name" =>  'required',
            "passport" =>  'required',
            "mobile_no" =>  'required',
            // Last Education Qualification validation
            'educations.education_level_id.*' => 'required', // Degree
            'educations.attend_from.*' => 'required|date',   // Year (From)
            'educations.attend_to.*' => 'required|date',     // Year (To)
            'educations.result.*' => 'required',             // Result
            'has_csca' => 'nullable|boolean',
            'csca_math' => 'required_if:has_csca,1|nullable|string|max:100',
            'csca_physics' => 'required_if:has_csca,1|nullable|string|max:100',
            'csca_chemistry' => 'required_if:has_csca,1|nullable|string|max:100',
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
            'educations.education_level_id.*.required' => 'The Degree field is required.',
            'educations.attend_from.*.required' => 'The Year Attended (From) field is required.',
            'educations.attend_to.*.required' => 'The Year Attended (To) field is required.',
            'educations.result.*.required' => 'The Result field is required.',
            'csca_math.required_if' => 'The Math result field is required when CSCA is enabled.',
            'csca_physics.required_if' => 'The Physics result field is required when CSCA is enabled.',
            'csca_chemistry.required_if' => 'The Chemistry result field is required when CSCA is enabled.',
        ];
    }
}
