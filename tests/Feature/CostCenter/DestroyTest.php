<?php

use App\Models\CostCenter;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should delete the cost center', function () {
    $costCenter = CostCenter::factory()->create();

    $this->assertModelExists($costCenter);

    $response = $this->deleteJson("/api/cost-centers/{$costCenter->id}");

    $this->assertModelMissing($costCenter);

    $response->assertOk()->assertMessage(trans('messages.cost_center.destroy'));

    $response->assertJson(function (AssertableJson $json) use ($costCenter) {
        $json->has('data', function (AssertableJson $json) use ($costCenter) {
            $json->where('id', $costCenter->id);

            $json->hasAll(['id', 'name', 'status', 'created_at', 'updated_at']);
        })->has('message');
    });
});
