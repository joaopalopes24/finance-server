<?php

use App\Models\CostCenter;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should show the cost center', function () {
    $costCenter = CostCenter::factory()->create();

    $response = $this->getJson("/api/cost-centers/{$costCenter->id}");

    $response->assertOk()->assertMessage(trans('messages.cost_center.show'));

    $response->assertJson(function (AssertableJson $json) use ($costCenter) {
        $json->has('data', function (AssertableJson $json) use ($costCenter) {
            $json->where('id', $costCenter->id);

            $json->hasAll(['id', 'name', 'status', 'created_at', 'updated_at']);
        })->has('message');
    });
});
