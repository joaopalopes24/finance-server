<?php

use App\Models\AccountPlan;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should show the account plan', function () {
    $accountPlan = AccountPlan::factory()->create();

    $response = $this->getJson("/api/account-plans/{$accountPlan->id}");

    $response->assertOk()->assertMessage(trans('messages.account_plan.show'));

    $response->assertJson(function (AssertableJson $json) use ($accountPlan) {
        $json->has('data', function (AssertableJson $json) use ($accountPlan) {
            $json->where('id', $accountPlan->id);

            $json->hasAll(['id', 'name', 'status', 'created_at', 'updated_at']);
        })->has('message');
    });
});
