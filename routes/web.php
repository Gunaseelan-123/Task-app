<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/shop', [StorefrontController::class, 'shop'])->name('shop');
Route::get('/products/{product:slug}', [StorefrontController::class, 'product'])->name('products.show');
Route::get('/search/suggestions', [StorefrontController::class, 'searchSuggestions'])->name('search.suggestions');
Route::get('/architecture', [StorefrontController::class, 'architecture'])->name('architecture');

Route::middleware('guest')->group(function (): void {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1');

    Route::get('/password/forgot', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/password/email', [AuthController::class, 'sendResetLink'])
        ->name('password.email')->middleware('throttle:6,1');

    Route::get('/password/reset/{token}', [AuthController::class, 'showResetPassword'])
        ->name('password.reset');

    Route::post('/password/reset', [AuthController::class, 'resetPassword'])
        ->name('password.update')->middleware('throttle:6,1');

    // ✅ OTP LOGIN (FIXED)
    Route::get('/otp/login', [AuthController::class, 'showOtpLogin'])->name('otp.login');
    Route::post('/otp/login', [AuthController::class, 'sendOtpLogin'])
        ->middleware('throttle:5,1');

    // ✅ OTP VERIFY (CORRECT)
    Route::get('/otp/verify', [AuthController::class, 'showOtpVerify'])
        ->name('otp.verify.form');

    Route::post('/otp/verify', [AuthController::class, 'verifyOtpLogin'])
        ->name('otp.verify')->middleware('throttle:5,1');

    // 2FA
    Route::get('/2fa/challenge', [AuthController::class, 'showTwoFactorChallenge'])
        ->name('2fa.challenge');

    Route::post('/2fa/challenge', [AuthController::class, 'verifyTwoFactor'])
        ->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/items/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{item}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::middleware('auth')->group(function (): void {
    Route::get('/account', [AccountController::class, 'dashboard'])->name('account.dashboard');
    Route::patch('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::post('/account/logout-other-devices', [AccountController::class, 'logoutOtherDevices'])->name('account.logout-other-devices');
    Route::patch('/account/profile-picture', [AccountController::class, 'updateProfilePicture'])->name('account.profile.picture.update');
    Route::delete('/account/profile-picture', [AccountController::class, 'removeProfilePicture'])->name('account.profile.picture.remove');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/address', [CheckoutController::class, 'storeAddress'])->name('checkout.address.store');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function (): void {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::get('/banners', [AdminBannerController::class, 'index'])->name('banners.index');
    Route::post('/banners', [AdminBannerController::class, 'store'])->name('banners.store');
    Route::patch('/banners/{banner}', [AdminBannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{banner}', [AdminBannerController::class, 'destroy'])->name('banners.destroy');
    Route::get('/coupons', [AdminCouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [AdminCouponController::class, 'store'])->name('coupons.store');
    Route::patch('/coupons/{coupon}', [AdminCouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [AdminCouponController::class, 'destroy'])->name('coupons.destroy');
}); 