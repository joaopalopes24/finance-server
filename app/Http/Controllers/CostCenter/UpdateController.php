<?php

namespace App\Http\Controllers\CostCenter;

use App\Http\Controllers\Controller;
use App\Http\Requests\CostCenter\UpdateRequest;
use App\Http\Resources\CostCenter\UpdateResource;
use App\Models\CostCenter;
use Illuminate\Http\JsonResponse;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CostCenter $costCenter, UpdateRequest $request): JsonResponse
    {
        $costCenter = tap($costCenter)->update($request->input());

        return UpdateResource::make($costCenter)->response();
    }
}
