<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\DestroyResource;
use App\Models\Transaction;

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Transaction $transaction)
    {
        $transaction->delete();

        return DestroyResource::make($transaction)->response();
    }
}
