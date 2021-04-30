<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class ArticleStoreRequest extends FormRequest
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
            'title' => 'required|string|max:512',
            'description' => 'required|string|max:1500',
            'body' => 'required|string',
            'language_id' => 'required|exists:languages,id',
            'categories' => 'required|array',
            'image' => 'sometimes|image|max:1000'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'A title is required',
            'description.required' => 'A description is required',
            'body.required' => 'A message is required',
            'categories.required' => 'Categories required',
            'language_id.exists' => 'Language not exists.'
        ];
    }
}
