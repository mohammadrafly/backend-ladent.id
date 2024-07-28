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

Route::resource('posts', PostController::class);

/*
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('posts', PostController::class);
    Route::resource('artist', ArtistController::class);
    Route::controller(AuthController::class)->group(function() {
        Route::get('auth/v1/logout', 'logout');
    });
});
*/

Route::controller(PostController::class)->group(function() {
    Route::get('posts/{slug}', 'show')->name('posts.slug');
    Route::get('posts/search/{query}', 'search')->name('posts.search');
    Route::get('posts/archive/{year}', 'year')->name('posts.year');
});
