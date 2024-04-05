<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

test('it should reset password using the valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->postJson('/api/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $response = $this->patchJson('/api/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertOk()->assertMessage(trans(Password::PASSWORD_RESET));

        return true;
    });
});

test('it should return error if user try to reset password with invalid token', function () {
    $user = User::factory()->create();

    $response = $this->patchJson('/api/reset-password', [
        'token' => Str::random(60),
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertInvalid(['email' => trans('passwords.token')]);
});
