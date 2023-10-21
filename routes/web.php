<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/login', [\App\Http\Controllers\Panel\LoginController::class, 'loginPage'])->name('login')->middleware('guest');
Route::post('/dashboard/login', [\App\Http\Controllers\Panel\LoginController::class, 'login'])->middleware(['throttle:20,1', 'guest']);
Route::post('/dashboard/logout', [\App\Http\Controllers\Panel\LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::group(['prefix' => 'dashboard', 'as' => 'admin.', 'middleware' => 'auth'], function () {
    Route::get('/', [\App\Http\Controllers\Panel\DashboardController::class, 'dashboard'])->name('dashboard');

    // store
    Route::group(['prefix' => 'store', 'as' => 'store.'], function () {
        Route::resource('category', \App\Http\Controllers\Panel\Store\CategoryController::class);
        Route::resource('product', \App\Http\Controllers\Panel\Store\ProductController::class);
        Route::post('product/brands-list-ajax', [\App\Http\Controllers\Panel\Store\ProductController::class, 'brandsListAjax'])->name('product.brands-list-ajax');
        Route::post('product/tags-list-ajax', [\App\Http\Controllers\Panel\Store\ProductController::class, 'tagsListAjax'])->name('product.tags-list-ajax');
    });

    // general
    Route::post('uploader', [\App\Http\Controllers\Panel\UploaderController::class, 'upload'])->name('uploader');
    Route::post('uploader/alt-text', [\App\Http\Controllers\Panel\UploaderController::class, 'setAltText'])->name('uploader.alt-text');
    Route::post('uploader/tinymce', [\App\Http\Controllers\Panel\UploaderController::class, 'tinymce'])->name('uploader.tinymce');
});
