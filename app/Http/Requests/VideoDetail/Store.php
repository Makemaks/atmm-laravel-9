<?php

namespace App\Http\Requests\VideoDetail;

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
            'video_category_id' => 'required|exists:video_categories,id',
            'title' => 'required|string',
            'video' => 'required|string',
            'image' => 'required|string',
            'date_release' => 'required|date',
            'show_in_explore' => 'required|string',
            'is_public' => 'required|string',
        ];
    }
}
