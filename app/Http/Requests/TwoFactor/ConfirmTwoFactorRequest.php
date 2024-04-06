<?php

namespace App\Http\Requests\TwoFactor;

use App\Support\TwoFactor\TwoFactorAuthentication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ConfirmTwoFactorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('two-factor-is-enabled');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'size:6',
            ],
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        if (! app(TwoFactorAuthentication::class)->verify($this->user(), $this->code)) {
            throw ValidationException::withMessages([
                'code' => trans('messages.two_factor.invalid_code'),
            ]);
        }
    }
}
