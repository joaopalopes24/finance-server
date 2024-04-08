<?php

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should update the new transaction', function () {
    $transaction = Transaction::inRandomOrder()->first();

    $otherTransaction = Transaction::factory()->costCenter()->accountPlan()->make();

    $this->assertDatabaseHas(Transaction::class, [
        'id' => $transaction->id,
        'description' => $transaction->description,
    ]);

    $response = $this->putJson("/api/transactions/{$transaction->id}", $otherTransaction->toArray());

    $this->assertDatabaseHas(Transaction::class, [
        'id' => $transaction->id,
        'description' => $otherTransaction->description,
    ]);

    $response->assertOk()->assertMessage(trans('messages.transaction.update'));

    $response->assertJson(function (AssertableJson $json) use ($transaction) {
        $json->has('data', function (AssertableJson $json) use ($transaction) {
            $json->where('id', $transaction->id);

            $json->hasAll(['id', 'cost_center_id', 'account_plan_id', 'description', 'amount', 'date', 'operation', 'status', 'created_at', 'updated_at']);
        })->has('message');
    });
});
