<?php

namespace App\Http\Controllers\Transaction;

use App\Actions\SaveTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\UpdateRequest;
use App\Http\Resources\Transaction\UpdateResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Transaction $transaction, UpdateRequest $request): JsonResponse
    {
        $transaction = SaveTransaction::handle($transaction, $request->input());

        return UpdateResource::make($transaction)->response();
    }
}
