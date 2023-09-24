<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin\ProductController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/{productId}', [ProductController::class, 'show'])->name('show');
});

Route::prefix('/cart')->name('cart.')->middleware('auth')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/', [CartController::class, 'store'])->name('store');
    Route::put('/{cart}', [CartController::class, 'update'])->name('update');
    Route::delete('/{cart}', [CartController::class, 'destroy'])->name('destroy');
});

Route::prefix('/customer')->name('customer.')->middleware('auth')->group(function () {
    Route::get('/profile', [CustomerController::class, 'show'])->name('show');
    Route::put('/profile', [CustomerController::class, 'update'])->name('update');
});

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('products')->resource('products', ProductController::class);
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/change-locale/{language}', [LocaleController::class, 'changeLocale'])->name('locale');

require __DIR__.'/auth.php';
