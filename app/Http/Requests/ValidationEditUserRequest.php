<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class ValidationEditUserRequest extends FormRequest
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
            'username' => 'required|string|alpha|max:31',
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->id, 
            'role_id' => 'required|numeric',
            'password' => 'string|min:6|max:20|confirmed|nullable',
            'password_confirmation' => 'string|min:6|max:20|nullable'
        ];
    }
}
