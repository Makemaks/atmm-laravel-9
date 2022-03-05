<?php

namespace App\Http\Requests\MusicDetail;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
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
            'audio' => 'string|nullable',
            'image' => 'string|nullable',
            'show_in_explore' => 'nullable',
            'is_public' => 'nullable'
        ];
    }
}
