<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('register', [\App\Http\Controllers\API\Auth\AuthController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\API\Auth\AuthController::class, 'login']);
    Route::post('user',  [\App\Http\Controllers\API\Auth\AuthController::class, 'user'])->middleware(['auth:api']);
    Route::post('logout', [\App\Http\Controllers\API\Auth\AuthController::class, 'logout'])->middleware(['auth:api']);
});
