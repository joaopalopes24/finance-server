<?php

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;

test('it should successfully register the new user', function () {
    Event::fake();

    $password = fake()->password();

    $response = $this->postJson('/api/register', [
        'name' => fake()->name(),
        'email' => fake()->email(),
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    Event::assertDispatched(Registered::class);

    $accessToken = $this->getPersonalAccessToken($response->json('data.token'));

    $this->assertModelExists($accessToken);

    $response
        ->assertOk()
        ->assertMessage(trans('auth.register'))
        ->assertJsonStructure(['message', 'data' => ['token']]);
});
