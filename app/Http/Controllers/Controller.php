<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    use AuthorizesRequests;
    public function successResponse(string $message, int $code = 200): JsonResponse
    {
        return response()->json([
            'message' => $message
        ], $code);
    }

    public function errorResponse(string $message, int $code = 500) : JsonResponse
    {
        return response()->json([
            'message' => $message
        ], $code);
    }
}
