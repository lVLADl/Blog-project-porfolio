<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\IndexController@index')->name('index');
Route::get('/dev', 'App\Http\Controllers\IndexController@dev');


Route::redirect('/articles', '/'); // ->name('articles.index');
// Route::view('article_itinerary', 'frontend.articles.article_itinerary', [])->name('articles.itinerary');


Route::prefix('articles')->name('articles.')->group(function () {
    Route::get('/{id}-{slug}', 'App\Http\Controllers\ArticleController@show')->name('show');

    // Пример AJAX-запроса для подгрузки статей
    // Route::get('/ajax/articles', [TravelController::class, 'ajaxArticles'])->name('ajax');
});
