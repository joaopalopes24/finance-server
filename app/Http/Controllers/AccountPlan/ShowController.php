<?php

namespace App\Http\Controllers\AccountPlan;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountPlan\ShowResource;
use App\Models\AccountPlan;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AccountPlan $accountPlan): JsonResponse
    {
        return ShowResource::make($accountPlan)->response();
    }
}
