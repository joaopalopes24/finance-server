<?php

namespace App\Http\Controllers\TwoFactor;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Get the QR Code for the user.
     */
    public function qrCode(Request $request): JsonResponse
    {
        $this->authorize('two-factor-is-enabled');

        $svg = $request->user()->twoFactorQrCodeSvg();
        $url = $request->user()->twoFactorQrCodeUrl();

        $payload = ['svg' => $svg, 'url' => $url];

        return $this->ok(trans('messages.two_factor.qr_code'), $payload);
    }

    /**
     * Get the secret key for the user.
     */
    public function secretKey(Request $request): JsonResponse
    {
        $this->authorize('two-factor-is-enabled');

        $secret = decrypt($request->user()->two_factor_secret);

        $payload = ['secretKey' => $secret];

        return $this->ok(trans('messages.two_factor.secret_key'), $payload);
    }

    /**
     * Get the recovery codes for the user.
     */
    public function recoveryCodes(Request $request): JsonResponse
    {
        $this->authorize('two-factor-is-confirmed');

        $codes = $request->user()->recoveryCodes();

        $payload = ['recoveryCodes' => $codes];

        return $this->ok(trans('messages.two_factor.recovery_codes'), $payload);
    }
}
