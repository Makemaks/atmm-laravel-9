<?php

namespace App\Http\Requests\MusicDetail;

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
            'title' => 'required|string',
            'audio' => 'required|string',
            'image' => 'required|string',
            'show_in_explore' => 'required|string',
            'is_public' => 'required|string'
        ];
    }
}
