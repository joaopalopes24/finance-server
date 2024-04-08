<?php

use App\Models\AccountPlan;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should return the dashboard report', function () {
    $count = AccountPlan::count();

    $response = $this->getJson('/api/dashboard');

    $response->assertJsonCount($count, 'data');

    $response->assertJson(function (AssertableJson $json) use ($count) {
        $json->has('data', $count, function (AssertableJson $json) {
            $json->hasAll(['name', 'transactions']);
        })->etc();
    });
});
