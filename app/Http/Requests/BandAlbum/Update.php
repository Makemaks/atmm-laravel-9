<?php

namespace App\Http\Requests\BandAlbum;

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
            'album' => 'required|string',
            'image' => 'nullable|string',
            'liner' => 'nullable|string',
            'release_date' => 'required|string',
            'is_public' => 'nullable'
        ];
    }
}
