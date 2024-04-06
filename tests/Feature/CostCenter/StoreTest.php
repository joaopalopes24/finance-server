<?php

use App\Models\CostCenter;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should create the new cost center', function () {
    $accountPlan = CostCenter::factory()->make();

    $this->assertDatabaseMissing(CostCenter::class, [
        'name' => $accountPlan->name,
    ]);

    $response = $this->postJson('/api/cost-centers', [
        'name' => $accountPlan->name,
        'status' => $accountPlan->status,
    ]);

    $this->assertDatabaseHas(CostCenter::class, [
        'name' => $accountPlan->name,
    ]);

    $response->assertCreated()->assertMessage(trans('messages.cost_center.store'));

    $response->assertJson(function (AssertableJson $json) {
        $json->has('data', function (AssertableJson $json) {
            $json->hasAll(['id', 'name', 'status', 'created_at', 'updated_at']);
        })->has('message');
    });
});

test('it should block the creation of a new cost center with the same name', function () {
    $accountPlan = CostCenter::factory()->create();

    $response = $this->postJson('/api/cost-centers', [
        'name' => $accountPlan->name,
        'status' => $accountPlan->status,
    ]);

    $response->assertUnprocessable()->assertInvalid([
        'name' => trans('validation.unique', ['attribute' => 'name']),
    ]);
});
