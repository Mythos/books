<?php

use App\Http\Controllers\SeriesController;
use App\Http\Livewire\Books\CreateBook;
use App\Http\Livewire\Categories\CreateCategory;
use App\Http\Livewire\Categories\EditCategory;
use App\Http\Livewire\Series\EditSeries;
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

    Route::get('category/new', CreateCategory::class)->name('categories.create');
    Route::prefix('{category}')->group(function () {
        Route::get('edit', EditCategory::class)->name('categories.edit');

        Route::get('series/new', SeriesController::class . '@create')->name('series.create');
        Route::get('{series}', SeriesController::class . '@show')->name('series.show');
        Route::post('series', SeriesController::class . '@store')->name('series.store');

        Route::prefix('{series}')->group(function () {
            Route::get('edit', EditSeries::class)->name('series.edit');

            Route::get('books/new', CreateBook::class)->name('books.create');
        });
    });
});
