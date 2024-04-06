<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

test('it should redirect to home because user already is verified', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/email/new-notification');

    $response->assertRedirect(frontend('/dashboard'));
});

test('it should send new email when requesting by user', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->postJson('/email/new-notification');

    Notification::assertSentTo([$user], VerifyEmail::class);

    $response->assertOk()->assertMessage(trans('auth.send_notification'));
});
