<?php

use App\Http\Livewire\Administration;
use App\Http\Livewire\Categories\CreateCategory;
use App\Http\Livewire\Categories\EditCategory;
use App\Http\Livewire\Categories\ShowCategory;
use App\Http\Livewire\ChangePassword;
use App\Http\Livewire\Profile;
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
    Route::prefix('books')->group(function (): void {
        Route::get('category/new', CreateCategory::class)->name('categories.create');
        Route::get('{category}', ShowCategory::class)->name('categories.show');
        Route::prefix('{category}')->group(function (): void {
            Route::get('edit', EditCategory::class)->name('categories.edit');

            Route::get('series/new', CreateSeries::class)->name('series.create');
            Route::get('{series}', ShowSeries::class)->name('series.show');

            Route::prefix('{series}')->group(function (): void {
                Route::get('edit', EditSeries::class)->name('series.edit');

                Route::get('volumes/new', CreateVolume::class)->name('volumes.create');
                Route::get('{number}/edit', EditVolume::class)->name('volumes.edit');
            });
        });
    });
});
Route::prefix('/admin')->middleware(['auth'])->group(function (): void {
    Route::get('/', Administration::class)->name('admin.index');
});
