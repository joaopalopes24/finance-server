<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

test('it should verify email successfully', function () {
    Event::fake();

    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        Carbon::now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);

    expect($user->refresh()->hasVerifiedEmail())->toBeTrue();

    $response->assertRedirect(frontend('/dashboard?verified=1'));

    // Trying to verify again
    $response = $this->actingAs($user)->get($verificationUrl);

    $response->assertRedirect(frontend('/dashboard?verified=1'));
});

test('it should not check email with invalid hash', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        Carbon::now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user)->get($verificationUrl)->assertForbidden();

    expect($user->refresh()->hasVerifiedEmail())->toBeFalse();
});

test('it should not check email with invalid user id', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        Carbon::now()->addMinutes(60),
        ['id' => fake()->randomNumber(5, true), 'hash' => sha1($user->email)]
    );

    $this->actingAs($user)->get($verificationUrl)->assertForbidden();

    expect($user->refresh()->hasVerifiedEmail())->toBeFalse();
});
