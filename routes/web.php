<?php

use App\Http\Controllers\Website\Auth\LoginController;
use App\Http\Controllers\Website\Auth\RegisterController;
use App\Http\Controllers\Website\CartItemController;
use App\Http\Controllers\Website\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['guest', 'throttle:login'])->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('register', [RegisterController::class, 'show'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register.submit');
});

Route::middleware(['auth', 'throttle:web'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::resource('cart-items', CartItemController::class)->except('show', 'create', 'edit');
