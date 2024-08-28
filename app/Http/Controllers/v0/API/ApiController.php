<?php

namespace App\Http\Controllers\v0\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public static function sendResponse(
        string|array|object|null $result,
        string $message,
        int $status = 200,
    ): JsonResponse {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => __($message),
            'status' => $status,
        ];

        return response()->json($response, $status, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public static function sendError(
        string $error,
        int $status,
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => __($error),
            'status' => $status,
        ];

        return response()->json($response, $status, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
