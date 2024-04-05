<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Event;

test('it should authenticate users successfully', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $accessToken = $this->getPersonalAccessToken($response->json('data.token'));

    $this->assertModelExists($accessToken);

    $response
        ->assertOk()
        ->assertMessage(trans('auth.login'))
        ->assertJsonStructure(['message', 'data' => ['token']]);
});

test('it should block authenticate because the password is invalid', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => fake()->password(10),
    ]);

    $this->assertGuest('sanctum');

    $response->assertUnprocessable()->assertInvalid(['email']);
});

test('it should logout user successfully', function () {
    $user = User::factory()->create();

    $token = $this->createToken($user);

    $this->assertModelExists($token->accessToken);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token->plainTextToken}",
    ])->deleteJson('/api/logout');

    $this->assertModelMissing($token->accessToken);

    $response->assertOk()->assertMessage(trans('auth.logout'));
});

test('it should block authenticate because of the too many requests', function () {
    Event::fake();

    $user = User::factory()->create();

    collect()->range(1, 5)->each(function () use ($user) {
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => fake()->password(10),
        ]);

        Event::assertNotDispatched(Lockout::class);

        $response->assertInvalid(['email' => trans('auth.failed')]);
    });

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => fake()->password(10),
    ]);

    $response->assertInvalid(['email']);

    Event::assertDispatched(Lockout::class);
});
