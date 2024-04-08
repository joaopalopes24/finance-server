<?php

namespace App\Http\Controllers\Transaction;

use App\Actions\SaveTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreRequest;
use App\Http\Resources\Transaction\StoreResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $transaction = SaveTransaction::handle(new Transaction, $request->input());

        return StoreResource::make($transaction)->response();
    }
}
