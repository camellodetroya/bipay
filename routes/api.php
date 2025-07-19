<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TransaccionController;

Route::middleware('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('usuarios', UserController::class);



    Route::post('/transacciones', [TransaccionController::class, 'store']);
    Route::get('/transacciones/export', [TransaccionController::class, 'export']);

    
});
