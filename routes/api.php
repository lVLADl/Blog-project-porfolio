<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->group(function () {
    Route::prefix('articles')->name('articles.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\ArticleController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Api\ArticleController::class, 'store'])->name('store');
        Route::prefix('/{id}')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\ArticleController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\Api\ArticleController::class, 'update'])->name('update');
            Route::delete('/', [\App\Http\Controllers\Api\ArticleController::class, 'destroy'])->name('destroy');

            // Route::get('/comments', [CommentController::class, 'index']);
        });
    });
});


Route::post('/article/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('article.comments.store');
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'searchAjax'])// ->middleware(['throttle:10,1'])
    ->name('search.ajax');
