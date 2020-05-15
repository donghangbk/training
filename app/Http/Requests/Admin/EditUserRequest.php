<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user->id, 
            'password' => 'string|min:6|max:20|confirmed|nullable',
            'password_confirmation' => 'string|min:6|max:20|nullable'
        ];
    }
}
