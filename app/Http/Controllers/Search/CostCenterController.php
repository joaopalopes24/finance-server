<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Http\Resources\Search\CostCenterResource;
use App\Models\CostCenter;
use Illuminate\Http\JsonResponse;

class CostCenterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        $costCenters = CostCenter::oldest('name')->get();

        return CostCenterResource::collection($costCenters)->response();
    }
}
