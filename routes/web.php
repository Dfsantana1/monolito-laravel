<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\FirebaseController;

Route::middleware('web')->post('/auth/firebase/callback', [FirebaseController::class, 'callback'])->name('auth.firebase.callback');

// Página principal - catálogo de productos
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/featured', [ProductController::class, 'featured'])->name('products.featured');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Rutas del carrito (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    
    // Rutas de órdenes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    
    // Rutas de pagos
    Route::get('/orders/{order}/payment', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/orders/{order}/payment', [PaymentController::class, 'process'])->name('payments.process');
    Route::get('/orders/{order}/payment/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/orders/{order}/payment/failure', [PaymentController::class, 'failure'])->name('payments.failure');
});

// Dashboard (requiere autenticación) - Redirige a productos
Route::get('/dashboard', [ProductController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Perfil de usuario (requiere autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
