<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\AuthController;

Route::prefix('auth/v1')->group(function() {
    Route::controller(AuthController::class)->group(function() {
        Route::post('login', 'login');
    });
    Route::middleware('auth:sanctum')->group( function () {
        Route::resource('posts', PostController::class);
        Route::resource('artist', ArtistController::class);
        Route::controller(AuthController::class)->group(function() {
            Route::get('auth/v1/logout', 'logout');
        });
    });
});

Route::controller(PostController::class)->group(function() {
    Route::get('posts', 'index')->name('posts.all');
    Route::get('posts/{slug}', 'findBySlug')->name('posts.slug');
    Route::get('posts/archive/{year}', 'findByYear')->name('posts.year');
    Route::get('posts/search/{query}', 'findBySearch')->name('posts.search');
});

Route::controller(ArtistController::class)->group(function() {
    Route::get('artists', 'index')->name('artists.all');
    Route::get('artists/{name}', 'findByName')->name('artists.name');
});
