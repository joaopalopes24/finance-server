<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * Status 200
     */
    public function ok(mixed $message = null, mixed $data = null): JsonResponse
    {
        $data = $this->generateData($data, $message, Response::HTTP_OK);

        return response()->json($data);
    }

    /**
     * Generate data for the response
     */
    private function generateData(mixed $data, mixed $message, int $statusCode): array
    {
        $message ??= trans(Response::$statusTexts[$statusCode]);

        return blank($data) ? ['message' => $message] : ['message' => $message, 'data' => $data];
    }
}
