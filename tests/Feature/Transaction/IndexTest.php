<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should return a list of transactions', function () {
    $response = $this->getJson('/api/transactions');

    $response->assertJson(function (AssertableJson $json) {
        $json->has('data', 10, function (AssertableJson $json) {
            $json->hasAll(['id', 'description', 'amount', 'date', 'operation', 'status', 'created_at', 'updated_at']);
        })->has('meta');
    });
});
