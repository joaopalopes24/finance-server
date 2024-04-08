<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Http\Resources\Search\AccountPlanResource;
use App\Models\AccountPlan;
use Illuminate\Http\JsonResponse;

class AccountPlanController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        $accountPlans = AccountPlan::oldest('name')->get();

        return AccountPlanResource::collection($accountPlans)->response();
    }
}
