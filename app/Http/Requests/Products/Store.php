<?php

namespace App\Http\Requests\Products;

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
            'nmi_api_plan_id' => 'required',
            'product_name' => 'required|string',
            'product_price' => 'required',
            'sales_tax' => 'required',
        ];
    }

    /**
     * Get the custom message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nmi_api_plan_id.required'  => 'NMI API PlanID is required',
            'product_name.required'  => 'Product name is required',
            'product_price.required'  => 'Product price is required',
            'sales_tax.required'  => 'Sales tax  is required',
        ];
    }

}
