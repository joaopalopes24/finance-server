<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->actingAs($this->user);
});

test('it should update your profile', function () {
    Event::fake();

    $newUser = User::factory()->make();

    $response = $this->putJson('/api/profile', [
        'name' => $newUser->name,
        'email' => $newUser->email,
    ]);

    $this->user->refresh();

    expect($this->user->name)->toBe($newUser->name);
    expect($this->user->email)->toBe($newUser->email);
    expect($this->user->email_verified_at)->toBeNull();

    $response->assertOk()->assertMessage(trans('messages.profile.update_profile'));

    Event::assertDispatched(Registered::class);

    $response->assertJson(function (AssertableJson $json) {
        $json->has('data', function (AssertableJson $json) {
            $json->where('id', $this->user->id);

            $json->hasAll(['id', 'name', 'email', 'email_verified_at', 'has_two_factor', 'created_at', 'updated_at']);
        })->has('message');
    });
});

test('it should update your profile when the email is unchanged', function () {
    Event::fake();

    $response = $this->putJson('/api/profile', [
        'email' => $this->user->email,
        'name' => $fake = fake()->name(),
    ]);

    $this->user->refresh();

    expect($this->user->name)->toBe($fake);
    expect($this->user->email)->toBe($this->user->email);
    expect($this->user->email_verified_at)->not->toBeNull();

    Event::assertNotDispatched(Registered::class);

    $response->assertOk()->assertMessage(trans('messages.profile.update_profile'));

    $response->assertJson(function (AssertableJson $json) {
        $json->has('data', function (AssertableJson $json) {
            $json->where('id', $this->user->id);

            $json->hasAll(['id', 'name', 'email', 'email_verified_at', 'has_two_factor', 'created_at', 'updated_at']);
        })->has('message');
    });
});
