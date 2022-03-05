<?php

namespace App\Http\Requests\InfusionForm;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
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
        return [
            'inf_field_FirstName' => 'required',
            'inf_field_LastName' => 'required',
            'inf_field_Email' => 'required|email'
        ];
    }
    public function messages()
    {
        return [
            'inf_field_FirstName.required' => 'First Name Field is required',
            'inf_field_LastName.required'  => 'Last Name Field is required',
            'inf_field_Email.required'  => 'Email field is required'
        ];
    }
}
