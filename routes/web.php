<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SeriesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => config('auth.registration_enabled')]);

Route::prefix('')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('category/new', CategoryController::class . '@create')->name('categories.create');
    Route::post('categories', CategoryController::class . '@store')->name('categories.store');
    Route::prefix('{category}')->group(function () {
        Route::get('edit', CategoryController::class . '@edit')->name('categories.edit');

        Route::get('series/new', SeriesController::class . '@create')->name('series.create');
        Route::get('{series}', SeriesController::class . '@show')->name('series.show');
        Route::post('series', SeriesController::class . '@store')->name('series.store');

        Route::prefix('{series}')->group(function () {
            Route::get('edit', SeriesController::class . '@edit')->name('series.edit');

            Route::get('books/new', BookController::class . '@create')->name('books.create');
            Route::post('books', BookController::class . '@store')->name('books.store');
            Route::get('{book}', BookController::class . '@show')->name('books.show');
        });
    });
});
