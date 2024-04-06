<?php

use App\Models\User;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;

beforeEach(function () {
    $this->user = User::factory()->twoFactor()->create();

    $this->code = app(Google2FA::class)->getCurrentOtp(decrypt($this->user->two_factor_secret));
});

test('it should return error because user dont exists', function () {
    Session::put([
        'session::login::id' => fake()->randomNumber(5, true),
        'session::login::remember' => true,
    ]);

    $response = $this->postJson('/two-factor', [
        'recover' => false,
        'code' => $this->code,
    ]);

    $this->assertGuest();

    $response->assertInvalid(['code' => trans('auth.invalid_code')]);
});

test('it should not validate the two factor before login', function () {
    $response = $this->postJson('/two-factor', [
        'recover' => false,
        'code' => $this->code,
    ]);

    $this->assertGuest();

    $response->assertInvalid(['code' => trans('auth.invalid_code')]);
});

test('it should return error because user insert incorrect code', function () {
    Session::put([
        'session::login::id' => $this->user->id,
        'session::login::remember' => true,
    ]);

    $response = $this->postJson('/two-factor', [
        'recover' => false,
        'code' => '123456',
    ]);

    $this->assertGuest();

    $response->assertInvalid(['code' => trans('auth.invalid_code')]);
});

test('it should user login using two factor code', function () {
    Session::put([
        'session::login::id' => $this->user->id,
        'session::login::remember' => true,
    ]);

    $response = $this->postJson('/two-factor', [
        'recover' => false,
        'code' => $this->code,
    ]);

    $this->assertAuthenticated();

    $this->assertAuthenticatedAs($this->user);

    $response->assertOk()->assertMessage(trans('auth.two_factor'));
});

test('it should return error because user insert incorrect recovery code', function () {
    Session::put([
        'session::login::id' => $this->user->id,
        'session::login::remember' => true,
    ]);

    $response = $this->postJson('/two-factor', [
        'recover' => true,
        'recovery_code' => '1111111111-1111111111',
    ]);

    $this->assertGuest();

    $response->assertInvalid(['recovery_code' => trans('auth.invalid_recovery')]);
});

test('it should user login using two factor recovery code', function () {
    Session::put([
        'session::login::id' => $this->user->id,
        'session::login::remember' => true,
    ]);

    $recoveryCode = collect($this->user->recoveryCodes())->first();

    $response = $this->postJson('/two-factor', [
        'recover' => true,
        'recovery_code' => $recoveryCode,
    ]);

    $this->assertAuthenticated();

    $this->assertAuthenticatedAs($this->user);

    expect($this->user->refresh()->recoveryCodes())->not->toContain($recoveryCode);

    $response->assertOk()->assertMessage(trans('auth.two_factor'));
});
