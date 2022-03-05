<?php

namespace App\Http\Requests\Explore;

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
            'inf_form_xid' => 'required|string',
            'inf_form_name' => 'required|string',
            'infusionsoft_version' => 'required|string',
            'inf_field_FirstName' => 'required|string',
            'inf_field_LastName' => 'required|string',
            'inf_field_Email' => 'required|string',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }
}
