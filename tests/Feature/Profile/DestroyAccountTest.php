<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);

    confirmPassword();
});

test('it should delete your own account', function () {
    $response = $this->deleteJson('/api/profile');

    $this->assertGuest('web');

    expect($this->user->fresh())->toBeNull();

    $response->assertOk()->assertMessage(trans('messages.profile.destroy_user'));
});
