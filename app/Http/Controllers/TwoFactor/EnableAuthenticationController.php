<?php

namespace App\Http\Controllers\TwoFactor;

use App\Http\Controllers\Controller;
use App\Support\TwoFactor\TwoFactorAuthentication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnableAuthenticationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('two-factor-is-disabled');

        $secret = app(TwoFactorAuthentication::class)->generateSecretKey();

        $request->user()->fill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => null,
            'two_factor_recovery_codes' => null,
        ])->save();

        return $this->ok(trans('messages.two_factor.enabled'));
    }
}
