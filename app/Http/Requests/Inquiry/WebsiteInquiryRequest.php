<?php

namespace App\Http\Requests\Inquiry;

use Illuminate\Foundation\Http\FormRequest;

class WebsiteInquiryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_no' => 'required|string|max:50',
            'country' => 'nullable|string|max:255',
            'country_preferred' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'last_education' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:5000',
            'oppurtunity' => 'nullable|string|max:50',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'type' => 'nullable|string|max:255',
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'mobile_no' => 'phone number',
            'country_preferred' => 'preferred country',
            'last_education' => 'last education',
        ];
    }
}
