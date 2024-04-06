<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->twoFactor()->create();

    $this->actingAs($this->user);

    confirmPassword();
});

test('it should block user access if 2FA is not enabled', function () {
    $this->user->fill(['two_factor_secret' => null])->save();

    $response = $this->deleteJson('/api/two-factor/destroy', [
        'password' => 'password',
    ]);

    $response->assertForbidden();
});

test('it should block user access if 2FA is not confirmed', function () {
    $this->user->fill(['two_factor_confirmed_at' => null])->save();

    $response = $this->deleteJson('/api/two-factor/destroy', [
        'password' => 'password',
    ]);

    $response->assertForbidden();
});

test('it should disable two factor authentication', function () {
    expect($this->user->two_factor_secret)->not->toBeNull();

    expect($this->user->two_factor_confirmed_at)->not->toBeNull();

    $response = $this->deleteJson('/api/two-factor/destroy', [
        'password' => 'password',
    ]);

    $response->assertOk()->assertMessage(trans('messages.two_factor.destroy'));

    expect($this->user->two_factor_secret)->toBeNull();

    expect($this->user->two_factor_confirmed_at)->toBeNull();
});
