<?php

use App\Models\User;
use Illuminate\Support\Carbon;
use PragmaRX\Google2FA\Google2FA;

beforeEach(function () {
    $this->user = User::factory()->secret()->create();

    $this->actingAs($this->user);

    confirmPassword();
});

test('it should block user access if 2FA is not enabled', function () {
    $this->user->fill(['two_factor_secret' => null])->save();

    $response = $this->postJson('/api/two-factor/confirm', [
        'code' => '123456',
    ]);

    $response->assertForbidden();
});

test('it should block user access if 2FA is confirmed', function () {
    $this->user->fill(['two_factor_confirmed_at' => Carbon::now()])->save();

    $response = $this->postJson('/api/two-factor/confirm', [
        'code' => '123456',
    ]);

    $response->assertForbidden();
});

test('it should return error because user insert incorrect code', function () {
    $response = $this->postJson('/api/two-factor/confirm', [
        'code' => '123456',
    ]);

    $response->assertInvalid(['code' => trans('messages.two_factor.invalid_code')]);
});

test('it should confirm two factor authentication', function () {
    expect($this->user->two_factor_secret)->not->toBeNull();

    expect($this->user->two_factor_confirmed_at)->toBeNull();

    expect($this->user->two_factor_recovery_codes)->toBeNull();

    $code = app(Google2FA::class)->getCurrentOtp(decrypt($this->user->two_factor_secret));

    $response = $this->postJson('/api/two-factor/confirm', [
        'code' => $code,
    ]);

    $response->assertOk()->assertMessage(trans('messages.two_factor.confirmed'));

    expect($this->user->two_factor_secret)->not->toBeNull();

    expect($this->user->two_factor_confirmed_at)->not->toBeNull();

    expect($this->user->two_factor_recovery_codes)->not->toBeNull();
});
