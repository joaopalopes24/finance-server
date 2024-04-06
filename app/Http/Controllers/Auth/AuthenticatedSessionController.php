<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $user = $request->authenticate();

        if ($user->hasTwoFactor()) {
            return $this->authorizeTwoFactor($user, $request);
        }

        return $this->authenticateUser($user, $request);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->ok(trans('auth.logout'));
    }

    /**
     * Redirect the user to the two factor challenge.
     */
    private function authorizeTwoFactor(User $user, LoginRequest $request): JsonResponse
    {
        $request->session()->put([
            'session::login::id' => $user->id,
            'session::login::remember' => $request->boolean('remember'),
        ]);

        return $this->found(trans('auth.require_two_factor'));
    }

    /**
     * Redirect the user to the dashboard.
     */
    private function authenticateUser(User $user, LoginRequest $request): JsonResponse
    {
        Auth::guard('web')->login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return $this->ok(trans('auth.login'));
    }
}
