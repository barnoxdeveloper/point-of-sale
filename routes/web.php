<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController, UserController, StoreController, CategoryController, ProductController, OrderController, OrderDetailController, OrderTemporaryController};

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

Route::middleware(['auth', 'verified', 'active', 'admin', 'revalidate'])
	->group(function()
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
    // route for product
    Route::resource('product', ProductController::class);
    Route::controller(ProductController::class)->group(function () {
		Route::post('delete-selected-product', 'deleteSelectedProduct')->name('delete-selected-product');
	});
    // route for Order
    Route::resource('order', OrderController::class);
    Route::controller(OrderController::class)->group(function () {
		Route::post('delete-selected-order', 'deleteSelectedOrder')->name('delete-selected-order');
	});
    // route for Order Detail
    Route::resource('order-detail', OrderDetailController::class);
    Route::controller(OrderDetailController::class)->group(function () {
		Route::post('delete-selected-order-detail', 'deleteSelectedOrderDetail')->name('delete-selected-order-detail');
	});
    // route for Order Temporary
    Route::resource('order-temporary', OrderTemporaryController::class);
    Route::controller(OrderTemporaryController::class)->group(function () {
		Route::post('delete-selected-order-temporary', 'deleteSelectedOrderTemporary')->name('delete-selected-order-temporary');
	});
});

require __DIR__.'/auth.php';
