<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\IndexRequest;
use App\Report\DashboardReport;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(IndexRequest $request): JsonResponse
    {
        $report = DashboardReport::handle($request->input());

        return $this->ok(data: $report);
    }
}
