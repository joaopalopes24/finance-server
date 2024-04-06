<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Support\TwoFactor\TwoFactorAuthentication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TwoFactorRequest extends FormRequest
{
    /**
     * Indicates if the user checked the "remember me" checkbox.
     */
    protected ?bool $remember = null;

    /**
     * The user that is being challenged.
     */
    protected ?User $challengedUser = null;

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $recover = $this->input('recover');

        return [
            'recover' => [
                'required',
                'boolean',
            ],
            'code' => [
                'nullable',
                Rule::requiredIf(! $recover),
                'string',
                'size:6',
            ],
            'recovery_code' => [
                'nullable',
                Rule::requiredIf($recover),
                'string',
                'size:21',
            ],
        ];
    }

    /**
     * Get the challenged user.
     */
    public function challengedUser(): User
    {
        if ($this->challengedUser) {
            return $this->challengedUser;
        }

        if (! $this->session()->has('session::login::id')) {
            $this->toResponse();
        }

        if (! $user = User::find($this->session()->get('session::login::id'))) {
            $this->toResponse();
        }

        return $this->challengedUser = $user;
    }

    /**
     * Handle a failed validation attempt.
     */
    public function toResponse(): void
    {
        [$key, $message] = empty($this->input('recovery_code'))
            ? ['code', trans('auth.invalid_code')]
            : ['recovery_code', trans('auth.invalid_recovery')];

        throw ValidationException::withMessages([$key => $message]);
    }

    /**
     * Determine if the request has a valid two-factor authentication code.
     */
    public function hasValidCode(): bool
    {
        return $this->input('code') && tap(app(TwoFactorAuthentication::class)->verify(
            $this->challengedUser(),
            $this->input('code')
        ), function ($result) {
            if ($result) {
                $this->session()->forget('session::login::id');
            }
        });
    }

    /**
     * Determine if the request has a valid two-factor authentication recovery code.
     */
    public function validRecoveryCode(): ?string
    {
        if (! $this->input('recovery_code')) {
            return null;
        }

        return tap(collect($this->challengedUser()->recoveryCodes())->first(function ($code) {
            return hash_equals($code, $this->input('recovery_code')) ? $code : null;
        }), function ($code) {
            if ($code) {
                $this->session()->forget('session::login::id');
            }
        });
    }

    /**
     * Get the remember me value.
     */
    public function remember(): bool
    {
        return $this->remember ?? (
            $this->remember = $this->session()->pull('session::login::remember', false)
        );
    }
}
