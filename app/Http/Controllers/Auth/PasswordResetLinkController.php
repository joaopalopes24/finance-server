<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordLinkRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PasswordLinkRequest $request): JsonResponse
    {
        $message = Password::sendResetLink($request->only(['email']));

        if ($message != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [trans($message)],
            ]);
        }

        return $this->ok(trans($message));
    }
}
