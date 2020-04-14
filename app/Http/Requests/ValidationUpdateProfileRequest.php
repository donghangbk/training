<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MatchOldPassword;

class ValidationUpdateProfileRequest extends FormRequest
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
            'description' => 'nullable|regex:/(([a-zA-z]+)(\d+)?$)/',
            'current_password' => ['nullable',new MatchOldPassword],
            'password' => 'nullable|string|min:6|max:20|confirmed|different:current_password',
            'password_confirmation' => 'string|min:6|max:20|nullable'
        ];
    }
}
