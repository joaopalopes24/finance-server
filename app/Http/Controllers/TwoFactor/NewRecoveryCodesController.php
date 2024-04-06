<?php

namespace App\Http\Controllers\TwoFactor;

use App\Http\Controllers\Controller;
use App\Support\TwoFactor\RecoveryCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewRecoveryCodesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('two-factor-is-confirmed');

        $codes = RecoveryCode::generateMany();

        $request->user()->fill(['two_factor_recovery_codes' => $codes])->save();

        return $this->ok(trans('messages.two_factor.new_recovery_codes'));
    }
}
