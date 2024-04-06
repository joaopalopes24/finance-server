<?php

namespace App\Http\Controllers;

use App\Http\Resources\MeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        return MeResource::make($user)->response();
    }
}
