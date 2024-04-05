<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(NewPasswordRequest $request): JsonResponse
    {
        $payload = $request->only(['token', 'email', 'password', 'password_confirmation']);

        $message = Password::reset($payload, $this->changePassword());

        if ($message != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [trans($message)],
            ]);
        }

        return $this->ok(trans($message));
    }

    /**
     * Update the password for the given user.
     */
    private function changePassword(): callable
    {
        return function ($user, $password) {
            $user->fill(['password' => $password])->save();

            event(new PasswordReset($user));
        };
    }
}
