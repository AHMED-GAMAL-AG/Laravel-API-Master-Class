<?php

namespace App\Exceptions\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptions
{
    public static array $handlers = [
        ValidationException::class => 'handleValidationException',
        NotFoundHttpException::class => 'handleNotFoundException',
        ModelNotFoundException::class => 'handleNotFoundException',
        AuthenticationException::class => 'handleAuthenticationException',
    ];

    public static function handleAuthenticationException(AuthenticationException $e, Request $request): JsonResponse
    {
        // log that sensitive stuff
        // should move this out to custom logger
        $source = 'Line: ' . $e->getLine() . ', File: ' . $e->getFile();
        Log::notice(basename(get_class($e)) . ' - ' . $e->getMessage() . ' - ' . $source);

        return response()->json([
            'error' => [
                'type' => basename(get_class($e)),
                'status' => 401,
                'message' => $e->getMessage()
            ]
        ]);
    }

    public static function handleValidationException(ValidationException $e, Request $request): JsonResponse
    {
        foreach ($e->errors() as $key => $value)
            foreach ($value as $message) {
                $errors[] = [
                    'type' => basename(get_class($e)),
                    'status' => 422,
                    'message' => $message,
                ];
            }

        return response()->json([
            'errors' => $errors
        ]);
    }

    public static function handleNotFoundException(ModelNotFoundException|NotFoundHttpException $e, Request $request): JsonResponse
    {
        return response()->json([
            'error' => [
                'type' => basename(get_class($e)),
                'status' => 404,
                'message' => 'Not Found ' . $request->getRequestUri()
            ]
        ]);
    }
}

