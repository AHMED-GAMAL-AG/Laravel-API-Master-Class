<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\AuthorTicketController;

Route::post('login', [AuthController::class, 'login'])
    ->name('api.v1.login');

Route::post('register', [AuthController::class, 'register'])
    ->name('api.v1.register');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('api.v1.logout');

    Route::apiResource('tickets', TicketController::class)
        ->names('api.v1.tickets');

    Route::apiResource('authors', AuthorController::class)
        ->names('api.v1.authors');

    Route::apiResource('authors.tickets', AuthorTicketController::class)
        ->names('api.v1.authors.tickets');
});
