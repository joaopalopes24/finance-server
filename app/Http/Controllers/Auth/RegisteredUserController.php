<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisteredUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisteredUserRequest $request): JsonResponse
    {
        $user = User::create($request->only(['name', 'email', 'password']));

        event(new Registered($user));

        $token = $user->createToken('api-token');

        return $this->ok(trans('auth.register'), ['token' => $token->plainTextToken]);
    }
}
