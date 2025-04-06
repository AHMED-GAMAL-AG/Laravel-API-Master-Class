<?php

namespace App\Traits;

trait apiResponses
{
    protected function success($message, $statusCode = 200) {
        return response()->json([
            'status' => 'success',
            'message' => $message,
        ], $statusCode);
    }
}
