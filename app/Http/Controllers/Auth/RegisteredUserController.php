<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisteredUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisteredUserRequest $request): JsonResponse
    {
        $user = User::create($request->only(['name', 'email', 'password']));

        event(new Registered($user));

        Auth::guard('web')->login($user);

        return $this->ok(trans('auth.register'));
    }
}
