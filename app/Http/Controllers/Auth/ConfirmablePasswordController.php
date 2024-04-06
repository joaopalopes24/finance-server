<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConfirmPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfirmablePasswordController extends Controller
{
    /**
     * Check if the user's password is confirmed.
     */
    public function show(Request $request): JsonResponse
    {
        $confirmedAt = $request->session()->get('auth.password_confirmed_at', 0);

        $timeout = $request->input('seconds', config('auth.password_timeout', 300));

        $payload = ['confirmed' => (time() - $confirmedAt) < $timeout];

        return $this->ok(trans('auth.confirmed_status'), $payload);
    }

    /**
     * Confirm the user's password.
     */
    public function store(ConfirmPasswordRequest $request): JsonResponse
    {
        $request->session()->put('auth.password_confirmed_at', time());

        return $this->ok(trans('auth.confirmable_password'));
    }
}
