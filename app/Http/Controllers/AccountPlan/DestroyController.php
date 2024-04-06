<?php

namespace App\Http\Controllers\AccountPlan;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountPlan\DestroyResource;
use App\Models\AccountPlan;
use Illuminate\Http\JsonResponse;

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AccountPlan $accountPlan): JsonResponse
    {
        $accountPlan->delete();

        return DestroyResource::make($accountPlan)->response();
    }
}
