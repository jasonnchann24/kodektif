<?php

namespace App\Http\Requests\Category;

use App\Rules\CategorySavingRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
            'name' => 'required|string',
            'parent_id' => [
                'nullable',
                'numeric',
                new CategorySavingRule()
            ]
        ];
    }
}
