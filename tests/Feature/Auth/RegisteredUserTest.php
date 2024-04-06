<?php

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

test('it should successfully register the new user', function () {
    Event::fake();

    $password = fake()->password(12);

    $response = $this->postJson('/register', [
        'name' => fake()->name(),
        'email' => fake()->email(),
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $this->assertAuthenticated();

    Event::assertDispatched(Registered::class);

    $response->assertOk()->assertMessage(trans('auth.register'));
});
