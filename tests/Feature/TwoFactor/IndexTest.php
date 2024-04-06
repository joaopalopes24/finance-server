<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->twoFactor()->create();

    $this->actingAs($this->user);

    confirmPassword();
});

/**
 * QR Code
 */
test('it should block user to get qr code if 2FA is not enabled', function () {
    $this->user->fill(['two_factor_secret' => null])->save();

    $response = $this->getJson('/api/two-factor/qr-code');

    $response->assertForbidden();
});

test('it should block user to get qr code if 2FA is confirmed', function () {
    $response = $this->getJson('/api/two-factor/qr-code');

    $response->assertForbidden();
});

test('it should to get qr code', function () {
    $this->user->fill(['two_factor_confirmed_at' => null])->save();

    $response = $this->getJson('/api/two-factor/qr-code');

    $this->user->refresh();

    $response->assertOk()
        ->assertMessage(trans('messages.two_factor.qr_code'))
        ->assertJsonPath('data.svg', $this->user->twoFactorQrCodeSvg())
        ->assertJsonPath('data.url', $this->user->twoFactorQrCodeUrl());
});

/**
 * Secret Key
 */
test('it should block user to get secret key if 2FA is not enabled', function () {
    $this->user->fill(['two_factor_secret' => null])->save();

    $response = $this->getJson('/api/two-factor/secret-key');

    $response->assertForbidden();
});

test('it should block user to get secret key if 2FA is confirmed', function () {
    $response = $this->getJson('/api/two-factor/secret-key');

    $response->assertForbidden();
});

test('it should to get secret key', function () {
    $this->user->fill(['two_factor_confirmed_at' => null])->save();

    $response = $this->getJson('/api/two-factor/secret-key');

    $this->user->refresh();

    $response
        ->assertOk()
        ->assertMessage(trans('messages.two_factor.secret_key'))
        ->assertJsonPath('data.secretKey', decrypt($this->user->two_factor_secret));
});

/**
 * Recovery Codes
 */
test('it should block user to get recovery codes if 2FA is not enabled', function () {
    $this->user->fill(['two_factor_secret' => null])->save();

    $response = $this->getJson('/api/two-factor/recovery-codes');

    $response->assertForbidden();
});

test('it should block user to get recovery codes if 2FA is not confirmed', function () {
    $this->user->fill(['two_factor_confirmed_at' => null])->save();

    $response = $this->getJson('/api/two-factor/recovery-codes');

    $response->assertForbidden();
});

test('it should to get recovery codes', function () {
    $response = $this->getJson('/api/two-factor/recovery-codes');

    $this->user->refresh();

    $response
        ->assertOk()
        ->assertMessage(trans('messages.two_factor.recovery_codes'))
        ->assertJsonPath('data.recoveryCodes', $this->user->recoveryCodes());
});
