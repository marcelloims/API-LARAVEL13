<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Weather\WeatherController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum')->name('login');
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('auth:sanctum');
        Route::get('/user/{id}', [AuthController::class, 'userDetail']);
    });

    Route::prefix('post')->group(function () {
        Route::get('/fetch', [PostController::class, 'fetch']);
        Route::get('/get', [PostController::class, 'getList']);
        Route::get('/detail/{id}', [PostController::class, 'detail']);
        Route::post('/save', [PostController::class, 'store']);
        Route::patch('/update/{id}', [PostController::class, 'update']);
        Route::delete('/delete/{id}', [PostController::class, 'destroy']);
    });

    Route::prefix('weather')->group(function () {
        Route::get('/index', [WeatherController::class,'show']);
    });
});
