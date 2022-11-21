<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController, UserController, StoreController, CategoryController, ProductController, OrderController, OrderDetailController, OrderTemporaryController};
use App\Http\Controllers\User\{CategoryUserController, ProductUserController, OrderUserController, OrderTemporaryUserController};

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

Route::middleware(['auth', 'verified', 'active', 'revalidate'])
	->group(function()
{
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'verified', 'active', 'admin', 'revalidate'])
	->group(function()
{

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
        Route::get('category-where-store/{id}', 'categoryWhereStore')->name('category-where-store');
		Route::post('delete-selected-category', 'deleteSelectedCategory')->name('delete-selected-category');
	});
    // route for product
    Route::resource('product', ProductController::class);
    Route::controller(ProductController::class)->group(function () {
        Route::get('product-where-store/{id}', 'productWhereStore')->name('product-where-store');
        Route::get('product-where-category/{id}', 'productWhereCategory')->name('product-where-category');
        Route::post('print-barcode', 'printBarcode')->name('print-barcode');
		Route::post('delete-selected-product', 'deleteSelectedProduct')->name('delete-selected-product');
	});
    // route for Order
    Route::resource('order', OrderController::class);
    Route::controller(OrderController::class)->group(function () {
        Route::get('order-where-store/{id}', 'orderWhereStore')->name('order-where-store');
		Route::get('print-invoice/{id}', 'printInvoice')->name('print-invoice');
		Route::post('delete-selected-order', 'deleteSelectedOrder')->name('delete-selected-order');
	});
    // route for Order Detail
    // Route::resource('order-detail', OrderDetailController::class);
    // Route::controller(OrderDetailController::class)->group(function () {
	// 	Route::post('delete-selected-order-detail', 'deleteSelectedOrderDetail')->name('delete-selected-order-detail');
	// });
    // route for Order Temporary
    Route::resource('order-temporary', OrderTemporaryController::class);
    // Route::controller(OrderTemporaryController::class)->group(function () {
	// 	Route::post('delete-selected-order-temporary', 'deleteSelectedOrderTemporary')->name('delete-selected-order-temporary');
	// });
});

Route::middleware(['auth', 'verified', 'active', 'manager', 'revalidate'])
->group(function()
{
    // route for category
    Route::resource('category-user', CategoryUserController::class);
    // route for product
    Route::resource('product-user', ProductUserController::class);
    Route::controller(ProductUserController::class)->group(function () {
        Route::get('product-user-where-category/{id}', 'productWhereCategory')->name('product-user-where-category');
        Route::post('print-barcode-user', 'printBarcode')->name('print-barcode-user');
        // Route::get('test', 'test')->name('test');
    });

    Route::resource('order-user', OrderUserController::class);
    Route::controller(OrderUserController::class)->group(function () {
		Route::get('print-invoice-user/{id}', 'printInvoice')->name('print-invoice-user');
	});

    // route for Order Temporary
    Route::resource('order-temporary-user', OrderTemporaryUserController::class);
});

Route::middleware(['auth', 'verified', 'active', 'cashier', 'revalidate'])
->group(function()
{

});

require __DIR__.'/auth.php';
