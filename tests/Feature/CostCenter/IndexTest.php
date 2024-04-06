<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should return a list of cost centers', function () {
    $response = $this->getJson('/api/cost-centers');

    $response->assertJson(function (AssertableJson $json) {
        $json->has('data', 10, function (AssertableJson $json) {
            $json->hasAll(['id', 'name', 'status', 'created_at', 'updated_at']);
        })->has('meta');
    });
});
