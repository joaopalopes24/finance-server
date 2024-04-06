<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

test('it should update your password', function () {
    $newPassword = fake()->password(10);

    $response = $this->patchJson('/api/profile/password', [
        'current_password' => 'password',
        'password' => $newPassword,
        'password_confirmation' => $newPassword,
    ]);

    $response->assertOk()->assertMessage(trans('messages.profile.update_password'));
});

test('it should not update your password because insert incorrect password', function () {
    $newPassword = fake()->password(10);

    $response = $this->patchJson('/api/profile/password', [
        'current_password' => fake()->password(10),
        'password' => $newPassword,
        'password_confirmation' => $newPassword,
    ]);

    $response->assertInvalid(['current_password' => trans('validation.current_password')]);
});
