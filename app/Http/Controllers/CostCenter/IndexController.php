<?php

namespace App\Http\Controllers\CostCenter;

use App\Http\Controllers\Controller;
use App\Http\Resources\CostCenter\IndexResource;
use App\Models\CostCenter;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        $costCenters = $this->getCostCenters();

        return IndexResource::collection($costCenters)->response();
    }

    /**
     * Get the cost centers.
     */
    private function getCostCenters()
    {
        return QueryBuilder::for(CostCenter::class)
            ->allowedFilters(['name', 'code'])
            ->allowedSorts(['name', 'code'])
            ->paginate(limit());
    }
}
