<?php

namespace App\Http\Controllers\AccountPlan;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountPlan\UpdateRequest;
use App\Http\Resources\AccountPlan\UpdateResource;
use App\Models\AccountPlan;
use Illuminate\Http\JsonResponse;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AccountPlan $accountPlan, UpdateRequest $request): JsonResponse
    {
        $accountPlan = tap($accountPlan)->update($request->input());

        return UpdateResource::make($accountPlan)->response();
    }
}
