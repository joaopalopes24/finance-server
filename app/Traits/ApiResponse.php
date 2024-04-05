<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Generate data for the response
     */
    private function generateData(mixed $data, mixed $message, int $statusCode): array
    {
        $message ??= trans(Response::$statusTexts[$statusCode]);

        return is_null($data) ? ['message' => $message] : ['message' => $message, 'data' => $data];
    }

    /**
     * Generic response
     */
    private function genericResponse(mixed $data, mixed $message, int $statusCode): JsonResponse
    {
        $data = $this->generateData($data, $message, $statusCode);

        return new JsonResponse($data, $statusCode);
    }

    /*
    |--------------------------------------------------------------------------
    | Successful Responses
    |--------------------------------------------------------------------------
    */

    /**
     * Status 200
     */
    public function ok(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_OK);
    }

    /**
     * Status 201
     */
    public function created(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_CREATED);
    }

    /**
     * Status 204
     */
    public function noContent(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_NO_CONTENT);
    }

    /*
    |--------------------------------------------------------------------------
    | Redirection Responses
    |--------------------------------------------------------------------------
    */

    /**
     * Status 302
     */
    public function found(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_FOUND);
    }

    /*
    |--------------------------------------------------------------------------
    | Client Error Responses
    |--------------------------------------------------------------------------
    */

    /**
     * Status 400
     */
    public function badRequest(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Status 401
     */
    public function unauthorized(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Status 403
     */
    public function forbidden(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Status 404
     */
    public function notFound(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Status 405
     */
    public function methodNotAllowed(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Status 409
     */
    public function conflict(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_CONFLICT);
    }

    /**
     * Status 422
     */
    public function unprocessable(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /*
    |--------------------------------------------------------------------------
    | Server Error Responses
    |--------------------------------------------------------------------------
    */

    /**
     * Status 500
     */
    public function internalServerError(mixed $message = null, mixed $data = null): JsonResponse
    {
        return $this->genericResponse($data, $message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
