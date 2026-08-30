<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\CommerceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/home', [CatalogController::class, 'home']);
    Route::get('/products', [CatalogController::class, 'products']);
    Route::get('/products/suggestions', [CatalogController::class, 'suggestions']);
    Route::get('/products/{product:slug}', [CatalogController::class, 'show']);

    Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:6,1');
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::post('/auth/otp/request', [AuthController::class, 'requestOtp'])->middleware('throttle:5,1');
    Route::post('/auth/otp/verify', [AuthController::class, 'verifyOtp'])->middleware('throttle:5,1');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/logout-all', [AuthController::class, 'logoutAll']);

        // Rider location updates (authenticated riders)
        Route::post('/riders/me/location', [\App\Http\Controllers\Api\LocationController::class, 'update']);

        Route::get('/cart', [CommerceController::class, 'cart']);
        Route::post('/cart/items', [CommerceController::class, 'addToCart']);
        Route::get('/wishlist', [CommerceController::class, 'wishlist']);
        Route::post('/wishlist', [CommerceController::class, 'addToWishlist']);
        Route::get('/addresses', [CommerceController::class, 'addresses']);
        Route::post('/addresses', [CommerceController::class, 'createAddress']);
        Route::post('/checkout/place-order', [CommerceController::class, 'placeOrder']);
        Route::get('/orders', [CommerceController::class, 'orders']);
        Route::post('/products/{product}/reviews', [CommerceController::class, 'addReview']);
    });
});
