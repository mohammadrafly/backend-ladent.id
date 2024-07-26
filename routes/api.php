<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\AuthController;

Route::prefix('auth/v1')->group(function() {
    Route::controller(AuthController::class)->group(function() {
        Route::post('login', 'login');
    });
});

Route::middleware('auth:sanctum')->group( function () {
    Route::resource('posts', PostController::class);
    Route::resource('artist', ArtistController::class);
    Route::controller(AuthController::class)->group(function() {
        Route::get('auth/v1/logout', 'logout');
    });
});
