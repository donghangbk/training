<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationCreateUserRequest extends FormRequest
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
            'username' => 'required|string|max:31',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|numeric',
            'description' => 'nullable|regex:/(([a-zA-z]+)(\d+)?$)/'
        ];
    }
}
