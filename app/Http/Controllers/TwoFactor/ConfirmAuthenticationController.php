<?php

namespace App\Http\Controllers\TwoFactor;

use App\Http\Controllers\Controller;
use App\Http\Requests\TwoFactor\ConfirmTwoFactorRequest;
use App\Support\TwoFactor\RecoveryCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class ConfirmAuthenticationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ConfirmTwoFactorRequest $request): JsonResponse
    {
        $this->authorize('two-factor-is-enabled');

        $codes = RecoveryCode::generateMany();

        $request->user()->fill([
            'two_factor_recovery_codes' => $codes,
            'two_factor_confirmed_at' => Carbon::now(),
        ])->save();

        return $this->ok(trans('messages.two_factor.confirmed'));
    }
}
