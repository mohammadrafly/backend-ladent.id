<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PodcastController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CustomAuthenticate;

Route::prefix('auth/v1')->group(function() {
    Route::controller(AuthController::class)->group(function() {
        Route::post('login', 'login');
    });

    Route::middleware(CustomAuthenticate::class)->group( function () {
        Route::resource('posts', PostController::class);
        Route::resource('artist', ArtistController::class);
        Route::resource('podcasts', PodcastController::class);
        Route::prefix('users')->group(function() {
            Route::controller(UserController::class)->group(function() {
                Route::get('detail/{user}', 'detail');
            });
        });
        Route::controller(AuthController::class)->group(function() {
            Route::post('logout', 'logout');
        });
    });
});

Route::get('user', [AuthController::class, 'user']);

Route::controller(PostController::class)->group(function() {
    Route::get('posts', 'index');
    Route::get('posts/{slug}', 'findBySlug');
    Route::get('posts/archive/{year}', 'findByYear');
    Route::get('posts/search/{query}', 'findBySearch');
});

Route::controller(ArtistController::class)->group(function() {
    Route::get('artists', 'index');
    Route::get('artists/{name}', 'findByName');
});

Route::controller(PodcastController::class)->group(function() {
    Route::get('podcasts', 'index');
});

Route::resource('podcasts', PodcastController::class);
