<?php

namespace App\Http\Controllers\AccountPlan;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountPlan\IndexRequest;
use App\Http\Resources\AccountPlan\IndexResource;
use App\Models\AccountPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(IndexRequest $request): JsonResponse
    {
        $accountPlans = $this->getAccountPlans($request);

        return IndexResource::collection($accountPlans)->response();
    }

    /**
     * Get the account plans.
     */
    private function getAccountPlans(IndexRequest $request): LengthAwarePaginator
    {
        [$sort, $field, $search] = $request->values();

        return AccountPlan::query()
            ->search($search)
            ->orderBy($field, $sort)
            ->paginate(limit());
    }
}
