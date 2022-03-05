<?php

namespace App\Http\Requests\VideoDetail;

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
            'video_category_id' => 'required|exists:video_categories,id',
            'title' => 'required|string',
            'video' => 'string|nullable',
            'image' => 'string|nullable',
            'date_release' => 'required|date',
            'show_in_explore' => 'nullable',
            'is_public' => 'nullable'
        ];
    }
}
