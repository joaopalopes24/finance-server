<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Status 200
     */
    public function ok(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_OK);
    }

    /**
     * Status 204
     */
    public function noContent(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_NO_CONTENT);
    }

    /**
     * Status 302
     */
    public function found(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_FOUND);
    }

    /**
     * Generate data for the response
     */
    private function generateData(mixed $data, mixed $message, int $statusCode): array
    {
        $message ??= trans(Response::$statusTexts[$statusCode]);

        return blank($data) ? ['message' => $message] : ['message' => $message, 'data' => $data];
    }

    /**
     * Generic response
     */
    private function genericResponse(mixed $data, mixed $message, int $statusCode): JsonResponse
    {
        $data = $this->generateData($data, $message, $statusCode);

        return new JsonResponse($data, $statusCode);
    }
}
