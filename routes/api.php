<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/v1'], function () {
    Route::group(['prefix' => '/auth', 'controller' => AuthController::class], function () {
        Route::post('/login', 'login');
    });
    
    Route::group(['prefix' => '/tasks', 'middleware' => 'auth:sanctum', 'controller' => TaskController::class], function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
});
