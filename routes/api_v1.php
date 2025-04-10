<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\UserController;

Route::post('login', [AuthController::class, 'login'])
    ->name('api.v1.login');

Route::post('register', [AuthController::class, 'register'])
    ->name('api.v1.register');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('api.v1.logout');

    Route::apiResource('tickets', TicketController::class)
        ->names('api.v1.tickets');

    Route::apiResource('users', UserController::class)
        ->names('api.v1.users');
});
