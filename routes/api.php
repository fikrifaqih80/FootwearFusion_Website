<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\AuthController;



Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

Route::post('products/change-status', [ProductController::class, 'changeStatus']);
Route::get('sub-categories', [ProductController::class, 'getSubCategories']);
Route::get('child-categories', [ProductController::class, 'getChildCategories']);

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::put('/{id}/status', [OrderController::class, 'updateOrderStatus']);
    Route::put('/{id}/payment-status', [OrderController::class, 'updatePaymentStatus']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
});

Route::prefix('home')->group(function () {
    Route::get('data', [HomeController::class, 'getHomePageData']);
    Route::get('vendors', [HomeController::class, 'getVendorPageData']);
    Route::get('vendors/{id}/products', [HomeController::class, 'getVendorProductsPageData']);
    Route::get('product/{id}', [HomeController::class, 'showProductModal']);
});

Route::prefix('checkout')->group(function () {
    Route::get('address-data', [CheckoutController::class, 'getAddressData']);
    Route::get('shipping-methods', [CheckoutController::class, 'getShippingMethods']);
    Route::post('create-address', [CheckoutController::class, 'createAddress']);
    Route::post('submit-form', [CheckoutController::class, 'checkOutFormSubmit']);
});

Route::prefix('cart')->group(function () {
    Route::post('add', [CartController::class, 'addToCart']);
    Route::post('update', [CartController::class, 'updateProductQty']);
    Route::get('total', [CartController::class, 'cartTotal']);
    Route::get('count', [CartController::class, 'getCartCount']);
    Route::get('products', [CartController::class, 'getCartProducts']);
    Route::delete('remove/{rowId}', [CartController::class, 'removeProduct']);
    Route::post('coupon/apply', [CartController::class, 'applyCoupon']);
    Route::get('coupon/calculate', [CartController::class, 'couponCalculation']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);
