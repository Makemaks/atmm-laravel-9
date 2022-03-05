<?php

namespace App\Http\Requests\MusicDetail;

use Illuminate\Foundation\Http\FormRequest;

class AddAuthorToSong extends FormRequest
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
            'music_detail_id' => 'required|integer|exists:music_details,id',
            'author_id' => 'required|integer|exists:authors,id'
        ];
    }
}
