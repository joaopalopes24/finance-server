<?php

use App\Models\AccountPlan;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should create the new account plan', function () {
    $accountPlan = AccountPlan::factory()->make();

    $this->assertDatabaseMissing(AccountPlan::class, [
        'name' => $accountPlan->name,
    ]);

    $response = $this->postJson('/api/account-plans', [
        'name' => $accountPlan->name,
        'status' => $accountPlan->status,
    ]);

    $this->assertDatabaseHas(AccountPlan::class, [
        'name' => $accountPlan->name,
    ]);

    $response->assertCreated()->assertMessage(trans('messages.account_plan.store'));

    $response->assertJson(function (AssertableJson $json) {
        $json->has('data', function (AssertableJson $json) {
            $json->hasAll(['id', 'name', 'status', 'created_at', 'updated_at']);
        })->has('message');
    });
});

test('it should block the creation of a new account plan with the same name', function () {
    $accountPlan = AccountPlan::factory()->create();

    $response = $this->postJson('/api/account-plans', [
        'name' => $accountPlan->name,
        'status' => $accountPlan->status,
    ]);

    $response->assertUnprocessable()->assertInvalid(['name' => trans('validation.unique')]);
});
