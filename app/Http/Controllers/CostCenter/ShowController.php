<?php

namespace App\Http\Controllers\CostCenter;

use App\Http\Controllers\Controller;
use App\Http\Resources\CostCenter\ShowResource;
use App\Models\CostCenter;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CostCenter $costCenter): JsonResponse
    {
        return ShowResource::make($costCenter)->response();
    }
}
