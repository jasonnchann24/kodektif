<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
            'title' => ['sometimes', 'string', 'max:512'],
            'description' => ['sometimes', 'string', 'max:1500'],
            'body' => ['sometimes', 'string'],
            'language_id' => ['sometimes', 'exists:languages,id'],
            'categories' => ['sometimes', 'array']
        ];
    }
}
