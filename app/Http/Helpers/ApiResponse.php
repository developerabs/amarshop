<?php
namespace App\Http\Helpers;

class ApiResponse
{
    public static function success(
        string $message = 'Success',
        mixed $data = null,
        int $status = 200
    ) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    public static function error(
        string|array $message = 'Something went wrong',
        mixed $errors = null,
        int $status = 400
    ) {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    public static function warning(
        string $message = 'Warning',
        mixed $data = null,
        int $status = 422
    ) {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $data,
            'type'    => 'warning',
        ], $status);
    }
}