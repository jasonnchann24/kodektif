<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileStoreRequest extends FormRequest
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
            'country' => 'nullable|string',
            'about' => 'nullable|string',
            'facebook_link' => 'nullable|string',
            'linkedin_link' => 'nullable|string',
            'github_link' => 'nullable|string',
            'others_link' => 'nullable|string',
        ];
    }
}
