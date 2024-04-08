<?php

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should create the new transaction', function () {
    $transaction = Transaction::factory()->costCenter()->accountPlan()->make();

    $this->assertDatabaseMissing(Transaction::class, [
        'description' => $transaction->description,
    ]);

    $response = $this->postJson('/api/transactions', $transaction->toArray());

    $this->assertDatabaseHas(Transaction::class, [
        'description' => $transaction->description,
    ]);

    $response->assertCreated()->assertMessage(trans('messages.transaction.store'));

    $response->assertJson(function (AssertableJson $json) {
        $json->has('data', function (AssertableJson $json) {
            $json->hasAll(['id', 'cost_center_id', 'account_plan_id', 'description', 'amount', 'date', 'operation', 'status', 'created_at', 'updated_at']);
        })->has('message');
    });
});
