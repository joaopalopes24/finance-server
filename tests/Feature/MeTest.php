<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should return the authenticated user', function () {
    $response = $this->getJson('/api/me');

    $response->assertOk()->assertMessage(trans('messages.me'));

    $response->assertJson(function (AssertableJson $json) {
        $json->has('data', function (AssertableJson $json) {
            $json->where('id', $this->user->id);

            $json->hasAll(['id', 'name', 'email', 'email_verified_at', 'has_two_factor', 'created_at', 'updated_at']);
        })->has('message');
    });
});
