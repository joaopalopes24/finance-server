<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->twoFactor()->create();

    $this->actingAs($this->user);

    confirmPassword();
});

test('it should block user access if 2FA is not enabled', function () {
    $this->user->fill(['two_factor_secret' => null])->save();

    $response = $this->postJson('/api/two-factor/recovery-codes');

    $response->assertForbidden();
});

test('it should block user access if 2FA is not confirmed', function () {
    $this->user->fill(['two_factor_confirmed_at' => null])->save();

    $response = $this->postJson('/api/two-factor/recovery-codes');

    $response->assertForbidden();
});

test('it should update recovery codes', function () {
    $codes = $this->user->recoveryCodes();

    $response = $this->postJson('/api/two-factor/recovery-codes');

    $response->assertOk()->assertMessage(trans('messages.two_factor.new_recovery_codes'));

    expect($this->user->recoveryCodes())->not->toBe($codes);
});
