<?php

use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);

    confirmPassword();
});

test('it should block user access if 2FA is confirmed', function () {
    $user = User::factory()->twoFactor()->make();

    $this->user->fill([
        'two_factor_confirmed_at' => Carbon::now(),
        'two_factor_secret' => $user->two_factor_secret,
        'two_factor_recovery_codes' => $user->two_factor_recovery_codes,
    ])->save();

    $response = $this->postJson('/api/two-factor/enable');

    $response->assertForbidden();
});

test('it should enable two factor authentication', function () {
    expect($this->user->two_factor_secret)->toBeNull();

    expect($this->user->two_factor_confirmed_at)->toBeNull();

    expect($this->user->two_factor_recovery_codes)->toBeNull();

    $response = $this->postJson('/api/two-factor/enable');

    $response->assertOk()->assertMessage(trans('messages.two_factor.enabled'));

    expect($this->user->two_factor_secret)->not->toBeNull();

    expect($this->user->two_factor_confirmed_at)->toBeNull();

    expect($this->user->two_factor_recovery_codes)->toBeNull();
});
