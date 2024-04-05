<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(Auth::guard('sanctum')->check(), Response::HTTP_UNAUTHORIZED);

        abort_if($this->shouldVerifyEmail($request), Response::HTTP_CONFLICT, trans('auth.unverified'));

        return $next($request);
    }

    /**
     * Check if the user is unverified.
     */
    private function shouldVerifyEmail(Request $request): bool
    {
        return ! $request->user() || ! $request->user()->hasVerifiedEmail();
    }
}
