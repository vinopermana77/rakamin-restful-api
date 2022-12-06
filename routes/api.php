<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


Route::post('login', [UserController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('logout', [UserController::class, 'logout']);
    Route::group(['prefix' => 'V1'], function () {
        Route::apiResource('/category', CategoryController::class);
        Route::apiResource('/article', ArticleController::class);
    });
});




// Route::get('/article-show/{$id}', [ArticleController::class, 'show']);
// Route::post('/article-post', [ArticleController::class, 'store']);
// Route::post('/category-post', [CategoryController::class, 'store']);
