<?php

namespace App\Actions;

use App\Models\Transaction;
use App\Support\Action;

class SaveTransaction extends Action
{
    /**
     * The transaction data.
     */
    private array $data;

    /**
     * The transaction instance.
     */
    private Transaction $transaction;

    /**
     * Create a new action instance.
     */
    public function __construct(Transaction $transaction, array $data)
    {
        $this->data = $data;

        $this->transaction = $transaction;
    }

    /**
     * Execute the action.
     */
    protected function execute(): Transaction
    {
        $transaction = tap($this->transaction->fill(
            $this->data
        ))->save();

        return $transaction->refresh();
    }
}
