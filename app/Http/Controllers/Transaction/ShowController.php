<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\ShowResource;
use App\Models\Transaction;

class ShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Transaction $transaction)
    {
        return ShowResource::make($transaction)->response();
    }
}
