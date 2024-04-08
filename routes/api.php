<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/v1'], function () {
    Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
        Route::post('/login', 'login');
    });
    
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('/tasks', TaskController::class);
    });
});
