<?php

namespace App\Http\Requests\BandAlbum;

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
            'album' => 'required|string',
            'image' => 'required|string',
            'liner' => 'sometimes|string',
            'release_date' => 'required|string',
            'is_public' => 'required|string'
        ];
    }
}
