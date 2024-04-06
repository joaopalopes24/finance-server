<?php

use App\Models\CostCenter;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should update the new cost center', function () {
    $costCenter = CostCenter::factory()->create();

    $otherCostCenter = CostCenter::factory()->make();

    $this->assertDatabaseHas(CostCenter::class, [
        'id' => $costCenter->id,
        'name' => $costCenter->name,
    ]);

    $response = $this->putJson("/api/cost-centers/{$costCenter->id}", [
        'status' => $costCenter->status,
        'name' => $otherCostCenter->name,
    ]);

    $this->assertDatabaseHas(CostCenter::class, [
        'id' => $costCenter->id,
        'name' => $otherCostCenter->name,
    ]);

    $response->assertOk()->assertMessage(trans('messages.cost_center.update'));

    $response->assertJson(function (AssertableJson $json) use ($costCenter) {
        $json->has('data', function (AssertableJson $json) use ($costCenter) {
            $json->where('id', $costCenter->id);

            $json->hasAll(['id', 'name', 'status', 'created_at', 'updated_at']);
        })->has('message');
    });
});

test('it should block the update this cost center with other cost center name', function () {
    $costCenter = CostCenter::factory()->create();

    $response = $this->putJson("/api/cost-centers/{$costCenter->id}", [
        'name' => $costCenter->name,
        'status' => $costCenter->status,
    ]);

    $response->assertUnprocessable()->assertInvalid([
        'name' => trans('validation.unique', ['attribute' => 'name']),
    ]);
});
