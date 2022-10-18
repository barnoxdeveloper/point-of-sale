<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController, UserController};

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

Route::middleware(['auth', 'verified'])
	->group(function()
{
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // route for user
    Route::resource('user', UserController::class);
    Route::controller(UserController::class)->group(function () {
		// Route::post('delete-selected-user', 'deleteSelectedUser')->name('delete-selected-user');
	});
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
