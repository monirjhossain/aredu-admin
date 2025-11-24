<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
// use Request;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'branch' => 'required',
        ];
        if (Request::isMethod('post')){
            $rules['email'] = 'required|unique:users,email,NULL,id';
            $rules['pro_pic'] = 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048';
            // $rules['username'] = 'required|unique:users';
            $rules['mobile_no'] = 'required|unique:users';
            $rules['password'] = 'required';
        }else {
            $id = Request::input('id');
            $rules['email'] = 'required|email|unique:users,email,'.$id;
            // $rules['username'] = 'required|unique:users,username,'.$id;
            $rules['mobile_no'] = 'required|unique:users,mobile_no,'.$id;
        }
        // dd($rules);
        return $rules;
    }
}
