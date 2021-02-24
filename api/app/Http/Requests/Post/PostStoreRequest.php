<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:512'],
            'description' => ['required', 'string', 'max:1500'],
            'body' => ['required', 'string'],
            'language_id' => ['required', 'exists:languages,id'],
            'categories' => ['required', 'array']
        ];
    }
}
