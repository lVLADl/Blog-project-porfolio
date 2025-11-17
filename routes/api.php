<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::get('/comments', [CommentController::class, 'index']);
Route::post('/article/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('article.comments.store');
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'searchAjax'])// ->middleware(['throttle:10,1'])
    ->name('search.ajax');
