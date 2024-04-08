<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\IndexRequest;
use App\Http\Resources\Transaction\IndexResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(IndexRequest $request): JsonResponse
    {
        $transactions = $this->getTransactions($request);

        return IndexResource::collection($transactions)->response();
    }

    /**
     * Get the transactions.
     */
    private function getTransactions(IndexRequest $request): LengthAwarePaginator
    {
        [$sort, $field, $search] = $request->values();

        return Transaction::search($search)
            ->orderBy($field, $sort)
            ->paginate(limit());
    }
}
