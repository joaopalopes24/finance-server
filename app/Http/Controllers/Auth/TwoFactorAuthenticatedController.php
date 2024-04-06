<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TwoFactorRequest;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthenticatedController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(TwoFactorRequest $request): mixed
    {
        $user = $request->challengedUser();

        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);
        } elseif (! $request->hasValidCode()) {
            return $request->toResponse();
        }

        Auth::guard('web')->login($user, $request->remember());

        $request->session()->regenerate();

        return $this->ok(trans('auth.two_factor'));
    }
}
