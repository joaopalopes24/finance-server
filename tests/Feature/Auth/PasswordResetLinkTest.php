<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

test('it should send to user the link to reset your password', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->postJson('/api/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('it should render reset password page using link', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->postJson('/api/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $token = $notification->token;

        $url = $this->invokeMethod($notification, 'resetUrl', [$user]);

        $frontend = frontend("/reset-password?token={$token}&email={$user->email}");

        expect($url)->toBe($frontend);

        return true;
    });
});

test('it should return throttled email request error', function () {
    $user = User::factory()->create();

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make(Str::random(60)),
        'created_at' => Carbon::now(),
    ]);

    $response = $this->postJson('/api/forgot-password', [
        'email' => $user->email,
    ]);

    $response->assertInvalid(['email' => trans('passwords.throttled')]);
});
