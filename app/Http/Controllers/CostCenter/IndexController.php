<?php

namespace App\Http\Controllers\CostCenter;

use App\Http\Controllers\Controller;
use App\Http\Requests\CostCenter\IndexRequest;
use App\Http\Resources\CostCenter\IndexResource;
use App\Models\CostCenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(IndexRequest $request): JsonResponse
    {
        $costCenters = $this->getCostCenters($request);

        return IndexResource::collection($costCenters)->response();
    }

    /**
     * Get the cost centers.
     */
    private function getCostCenters(IndexRequest $request): LengthAwarePaginator
    {
        [$sort, $field, $search] = $request->values();

        return CostCenter::query()
            ->search($search)
            ->orderBy($field, $sort)
            ->paginate(limit());
    }
}
