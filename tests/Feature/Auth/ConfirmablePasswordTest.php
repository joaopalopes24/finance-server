<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

test('it should check if user has the password confirmed', function () {
    $response = $this->getJson('/confirmed-status');

    $response->assertOk()->assertMessage(trans('auth.confirmed_status'));

    $response->assertJsonFragment(['confirmed' => false]);

    confirmPassword();

    $response = $this->getJson('/confirmed-status');

    $response->assertOk()->assertMessage(trans('auth.confirmed_status'));

    $response->assertJsonFragment(['confirmed' => true]);
});

test('it should successfully request password confirmation', function () {
    $response = $this->postJson('/confirm-password', [
        'password' => 'password',
    ]);

    $response->assertOk()->assertMessage(trans('auth.confirmable_password'));
});

test('it should fail in password confirmation', function () {
    $response = $this->postJson('/confirm-password', [
        'password' => 'wrong-password',
    ]);

    $response->assertInvalid(['password' => trans('validation.current_password')]);
});
