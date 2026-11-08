<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MarketController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'app');

Route::prefix('api')->group(function () {
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,15');
    Route::post('reset', [AuthController::class, 'reset'])->middleware('throttle:10,15');
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('state', [MarketController::class, 'state']);
    Route::get('users/{id}/pic', [MarketController::class, 'pic']);

    Route::middleware('auth')->group(function () {
        Route::put('profile', [MarketController::class, 'profile']);
        Route::put('expertise', [MarketController::class, 'expertise']);
        Route::post('jobs', [MarketController::class, 'postJob']);
        Route::post('jobs/{id}/apply', [MarketController::class, 'apply']);
        Route::post('jobs/{id}/hire', [MarketController::class, 'hire']);
        Route::post('jobs/{id}/done', [MarketController::class, 'done']);
        Route::post('jobs/{id}/review', [MarketController::class, 'review']);
        Route::post('messages', [MarketController::class, 'message']);
        Route::get('signals', [MarketController::class, 'signals']);
        Route::post('signal', [MarketController::class, 'signal']);
    });
});
