<?php

namespace App\Http\Controllers\CostCenter;

use App\Http\Controllers\Controller;
use App\Http\Resources\CostCenter\DestroyResource;
use App\Models\CostCenter;
use Illuminate\Http\JsonResponse;

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CostCenter $costCenter): JsonResponse
    {
        $costCenter->delete();

        return DestroyResource::make($costCenter)->response();
    }
}
