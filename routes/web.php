<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController, UserController, StoreController, CategoryController};

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

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function()
{
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // route for user
    Route::resource('user', UserController::class);
    Route::controller(UserController::class)->group(function () {
        Route::post('delete-selected-user', 'deleteSelectedUser')->name('delete-selected-user');
	});
    // route for store
    Route::resource('store', StoreController::class);
    Route::controller(StoreController::class)->group(function () {
		Route::post('delete-selected-store', 'deleteSelectedStore')->name('delete-selected-store');
	});
    // route for category
    Route::resource('category', CategoryController::class);
    Route::controller(CategoryController::class)->group(function () {
		Route::post('delete-selected-category', 'deleteSelectedCategory')->name('delete-selected-category');
	});
});

require __DIR__.'/auth.php';
