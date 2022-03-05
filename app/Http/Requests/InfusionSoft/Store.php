<?php

namespace App\Http\Requests\InfusionSoft;

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
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'password_confirmation' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'addrline1' => 'required',
            'city' => 'required',
            //'state' => 'state',
            'zipcode' => 'required',
            'country' => 'required',
            'phone' => 'required',  // mobile, home, other
            'cc_number' => 'required',
            'name_on_card' => 'required',
            'tos' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ];
    }
    public function messages()
    {
        return [
            'email.required'  => 'Email is required',
            'password.required' => 'Password is required',
            'password.confirmed' => 'Password does not match with Confirm Password',
            'password.min' => 'Password must have a minimum of 8 character',
            'password.regex' => 'Password should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character',
            'password_confirmation.required' => 'Confirm Password  is required',
            'fname.required' => 'Firstname is required',
            'lname.required'  => 'Lastname is required',
            'addrline1.required'  => 'Addres Line is required',
            'city.required'  => 'City is required',
            'zipcode.required'  => 'Zip Code is required',
            'country.required'  => 'Country is required',
            'phone.required'  => 'Phone is required',
            'cc_number.required'  => 'Credit Card number is required',
            'name_on_card.required'  => 'Name on Card is required',
            'tos.required'  => 'Please review and agree to the terms and conditions',
            'g-recaptcha-response.required'  => 'Captcha is required',
            'g-recaptcha-response.captcha'  => 'Invalid Captcha'
        ];
    }

}
