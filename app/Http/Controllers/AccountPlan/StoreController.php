<?php

namespace App\Http\Controllers\AccountPlan;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountPlan\StoreRequest;
use App\Http\Resources\AccountPlan\StoreResource;
use App\Models\AccountPlan;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $accountPlan = AccountPlan::create($request->input());

        return StoreResource::make($accountPlan)->response();
    }
}
