<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyEmailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        $this->checkUser($user, $request);

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(frontend('/dashboard?verified=1'));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(frontend('/dashboard?verified=1'));
    }

    /**
     * Check if the user is valid and the hash is correct.
     */
    private function checkUser(?User $user, Request $request): void
    {
        abort_if(is_null($user), Response::HTTP_FORBIDDEN);

        abort_unless(hash_equals(sha1($user->email), (string) $request->route('hash')), Response::HTTP_FORBIDDEN);
    }
}
