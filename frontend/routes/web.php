<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'shop'])->name('home');
Route::get('/shop', [PageController::class, 'shop'])->name('shop');
Route::get('/product/{slug}', [PageController::class, 'product'])->name('product');
Route::get('/cart', [PageController::class, 'cart'])->name('cart');
Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');
Route::get('/orders', [PageController::class, 'orders'])->name('orders');
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::get('/forgot-password', [PageController::class, 'forgotPassword'])->name('forgot-password');
Route::get('/reset-password', [PageController::class, 'resetPassword'])->name('reset-password');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
