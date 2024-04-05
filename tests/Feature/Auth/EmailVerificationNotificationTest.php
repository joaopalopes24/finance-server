<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

test('it should redirect to home because user already is verified', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/email/new-notification');

    $response->assertForbidden()->assertMessage(trans('auth.already_verified'));
});

test('it should send new email when requesting by user', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/email/new-notification');

    Notification::assertSentTo([$user], VerifyEmail::class);

    $response->assertOk()->assertMessage(trans('auth.send_notification'));
});
