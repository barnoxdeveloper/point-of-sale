<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{UserController, ProductController, CategoryController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    // route for logout
    Route::post('logout', [UserController::class, 'logout']);
    // route for fetch-user
    Route::get('user/fetch', [UserController::class, 'fetch']);
    // route for create-products
    // Route::get('product/create', [ProductController::class, 'create'])->name('product');
    // // route for edit-products
    // Route::get('product/edit', [ProductController::class, 'edit'])->name('product');
    // // route for update-products
    // Route::get('product/update', [ProductController::class, 'update'])->name('product');
    Route::resource('category', CategoryController::class)->except(['edit', 'create']);
    // Route::post('category', [CategoryController::class, 'store']);
    // Route::put('category/{id}', [CategoryController::class, 'update']);
    // Route::delete('category/{id}', [CategoryController::class, 'delete']);
});

// route for login
Route::post('login', [UserController::class, 'login']);
// route for fetch-products
Route::get('product', [ProductController::class, 'all'])->name('product');
