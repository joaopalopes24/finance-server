<?php

namespace App\Http\Controllers\CostCenter;

use App\Http\Controllers\Controller;
use App\Http\Requests\CostCenter\StoreRequest;
use App\Http\Resources\CostCenter\StoreResource;
use App\Models\CostCenter;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $costCenter = CostCenter::create($request->input());

        return StoreResource::make($costCenter)->response();
    }
}
