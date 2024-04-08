<?php

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();

    Sanctum::actingAs($this->user);
});

test('it should delete the transaction', function () {
    $transaction = Transaction::inRandomOrder()->first();

    $this->assertModelExists($transaction);

    $response = $this->deleteJson("/api/transactions/{$transaction->id}");

    $this->assertModelMissing($transaction);

    $response->assertOk()->assertMessage(trans('messages.transaction.destroy'));

    $response->assertJson(function (AssertableJson $json) use ($transaction) {
        $json->has('data', function (AssertableJson $json) use ($transaction) {
            $json->where('id', $transaction->id);

            $json->hasAll(['id', 'description', 'amount', 'date', 'operation', 'status', 'created_at', 'updated_at']);
        })->has('message');
    });
});
