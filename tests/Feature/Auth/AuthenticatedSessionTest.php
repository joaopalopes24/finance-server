<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Event;

test('it should authenticate users successfully', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();

    $response->assertOk()->assertMessage(trans('auth.login'));
});

test('it should block authenticate because the password is invalid', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/login', [
        'email' => $user->email,
        'password' => fake()->password(10),
    ]);

    $this->assertGuest();

    $response->assertUnprocessable()->assertInvalid(['email']);
});

test('it should logout user successfully', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->deleteJson('/logout');

    $this->assertGuest();

    $response->assertOk()->assertMessage(trans('auth.logout'));
});

test('it should block authenticate because of the too many requests', function () {
    Event::fake();

    $user = User::factory()->create();

    collect()->range(1, 5)->each(function () use ($user) {
        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => fake()->password(10),
        ]);

        Event::assertNotDispatched(Lockout::class);

        $response->assertInvalid(['email' => trans('auth.failed')]);
    });

    $response = $this->postJson('/login', [
        'email' => $user->email,
        'password' => fake()->password(10),
    ]);

    $response->assertInvalid(['email']);

    Event::assertDispatched(Lockout::class);
});

test('it should redirect user to two factor challenge', function () {
    $user = User::factory()->twoFactor()->create();

    $response = $this->postJson('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertGuest();

    $response->assertFound()->assertMessage(trans('auth.require_two_factor'));
});
