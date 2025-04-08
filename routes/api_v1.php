<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TicketController;


// test route /
Route::get('/', function () {
    return response()->json(['message' => 'API V1']);
});

Route::apiResource('tickets', TicketController::class);
