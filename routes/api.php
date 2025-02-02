<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AdminController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    // Common routes for all authenticated users
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Customer-specific routes
    Route::middleware('role:customer')->group(function () {
        Route::get('/restaurants', [CustomerController::class, 'getRestaurants']);
        Route::get('/restaurants/{id}/food-items', [CustomerController::class, 'getFoodItems']);
        Route::post('/orders', [CustomerController::class, 'placeOrder']);
        Route::get('/orders', [CustomerController::class, 'getOrderHistory']);
    });

    // Vendor-specific routes
    Route::middleware('role:vendor')->group(function () {
        Route::apiResource('restaurants', VendorController::class)->except(['index', 'show']);
        Route::apiResource('food-items', VendorController::class)->except(['index', 'show']);
        Route::get('/orders', [VendorController::class, 'getOrders']);
        Route::put('/orders/{id}/status', [VendorController::class, 'updateOrderStatus']);
    });

    // Admin-specific routes
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', AdminController::class);
        Route::apiResource('restaurants', AdminController::class);
        Route::get('/orders', [AdminController::class, 'getAllOrders']);
    });

    Route::apiResource('restaurants', RestaurantController::class);
    Route::apiResource('food-items', FoodItemController::class)->except(['store']);
    Route::post('/food-items/{restaurant}', [FoodItemController::class, 'store']);
    Route::apiResource('orders', OrderController::class);
});
