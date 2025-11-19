<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\PostController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');

// Product routes available for both guests and authenticated users
Route::get('/products', [ProductController::class, 'index'])->name('user.products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('user.products.show');
Route::get('/product', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::get('/about', function () {
    return view('page.about');
})->name('user.about');

// Posts routes available for both guests and authenticated users
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

// Cart and order routes require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // Cart routes (authentication required)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart', [CartController::class, 'buyNow'])->name('cart.buyNow');

    // Checkout routes (authentication required)
    Route::post('/checkout-preview', [CheckoutController::class, 'preview'])->name('checkout.preview');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // Order routes (authentication required)
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::get('/order/cancel/{id}', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::get('/order/receive/{id}', [OrderController::class, 'receive'])->name('order.receive');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');

    // Review routes
    Route::get('/review/create', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
    Route::get('/product/{id}/reviews', [ReviewController::class, 'getProductReviews'])->name('product.reviews');

});
