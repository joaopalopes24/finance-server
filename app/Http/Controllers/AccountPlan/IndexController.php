<?php

namespace App\Http\Controllers\AccountPlan;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountPlan\IndexResource;
use App\Models\AccountPlan;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        $accountPlans = $this->getAccountPlans();

        return IndexResource::collection($accountPlans)->response();
    }

    /**
     * Get the account plans.
     */
    private function getAccountPlans()
    {
        return QueryBuilder::for(AccountPlan::class)
            ->allowedFilters(['name', 'code'])
            ->allowedSorts(['name', 'code'])
            ->paginate(limit());
    }
}
