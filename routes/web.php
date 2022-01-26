<?php

use App\Http\Livewire\Administration;
use App\Http\Livewire\Articles\CreateArticle;
use App\Http\Livewire\Articles\EditArticle;
use App\Http\Livewire\Articles\ShowArticle;
use App\Http\Livewire\Categories\CreateCategory;
use App\Http\Livewire\Categories\EditCategory;
use App\Http\Livewire\Categories\ShowCategory;
use App\Http\Livewire\ChangePassword;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Publishers\CreatePublisher;
use App\Http\Livewire\Publishers\EditPublisher;
use App\Http\Livewire\Publishers\PublisherTable;
use App\Http\Livewire\Publishers\ShowPublisher;
use App\Http\Livewire\Series\CreateSeries;
use App\Http\Livewire\Series\EditSeries;
use App\Http\Livewire\Series\ShowSeries;
use App\Http\Livewire\Volumes\CreateVolume;
use App\Http\Livewire\Volumes\EditVolume;
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

Route::prefix('')->middleware(['auth'])->group(function (): void {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('profile', Profile::class)->name('profile');
    Route::get('profile/change-password', ChangePassword::class)->name('change-password');
    Route::prefix('admin')->group(function (): void {
        Route::get('/', Administration::class)->name('admin.index');
    });
    Route::prefix('publishers')->group(function (): void {
        Route::get('/', PublisherTable::class)->name('publishers.index');
        Route::get('new', CreatePublisher::class)->name('publishers.create');
        Route::get('{publisher}/edit', EditPublisher::class)->name('publishers.edit');
    });

    Route::get('category/new', CreateCategory::class)->name('categories.create');
    Route::get('{category}', ShowCategory::class)->name('categories.show');
    Route::prefix('{category}')->group(function (): void {
        Route::get('edit', EditCategory::class)->name('categories.edit');

        Route::prefix('books')->group(function (): void {
            Route::get('series/new', CreateSeries::class)->name('series.create');
            Route::get('{series}', ShowSeries::class)->name('series.show');

            Route::prefix('{series}')->group(function (): void {
                Route::get('edit', EditSeries::class)->name('series.edit');

                Route::get('volumes/new', CreateVolume::class)->name('volumes.create');
                Route::get('{number}/edit', EditVolume::class)->name('volumes.edit');
            });
        });
        Route::prefix('articles')->group(function (): void {
            Route::get('articles/new', CreateArticle::class)->name('article.create');
            Route::get('{article}', ShowArticle::class)->name('article.show');

            Route::prefix('{article}')->group(function (): void {
                Route::get('edit', EditArticle::class)->name('article.edit');
            });
        });
    });
});
