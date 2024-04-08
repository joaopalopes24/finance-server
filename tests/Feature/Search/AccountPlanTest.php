<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should return a list of account plans', function () {
    $response = $this->getJson('/api/search/account-plans');

    $response->assertJsonCount(10, 'data');

    $response->assertJson(function (AssertableJson $json) {
        $json->has('data', 10, function (AssertableJson $json) {
            $json->hasAll(['id', 'name']);
        });
    });
});
