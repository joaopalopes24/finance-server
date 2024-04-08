<?php

use App\Models\AccountPlan;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should update the new account plan', function () {
    $accountPlan = AccountPlan::factory()->create();

    $otherAccountPlan = AccountPlan::factory()->make();

    $this->assertDatabaseHas(AccountPlan::class, [
        'id' => $accountPlan->id,
        'name' => $accountPlan->name,
    ]);

    $response = $this->putJson("/api/account-plans/{$accountPlan->id}", [
        'status' => $accountPlan->status,
        'name' => $otherAccountPlan->name,
    ]);

    $this->assertDatabaseHas(AccountPlan::class, [
        'id' => $accountPlan->id,
        'name' => $otherAccountPlan->name,
    ]);

    $response->assertOk()->assertMessage(trans('messages.account_plan.update'));

    $response->assertJson(function (AssertableJson $json) use ($accountPlan) {
        $json->has('data', function (AssertableJson $json) use ($accountPlan) {
            $json->where('id', $accountPlan->id);

            $json->hasAll(['id', 'name', 'status', 'created_at', 'updated_at']);
        })->has('message');
    });
});

test('it should block the update this account plan with other account plan name', function () {
    $accountPlan = AccountPlan::factory()->create();

    $accountPlan2 = AccountPlan::factory()->create();

    $response = $this->putJson("/api/account-plans/{$accountPlan->id}", [
        'name' => $accountPlan2->name,
        'status' => $accountPlan->status,
    ]);

    $response->assertUnprocessable()->assertInvalid(['name' => trans('validation.unique')]);
});
