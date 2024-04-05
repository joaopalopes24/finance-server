<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class NewPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'token' => [
                'required',
                'string',
            ],
            'email' => [
                'required',
                'string',
                'max:255',
                'email',
            ],
            'password' => [
                'required',
                'string',
                'max:255',
                'confirmed',
                Password::defaults(),
            ],
        ];
    }
}
