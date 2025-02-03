<?php

use App\Http\Middleware\RoleMiddleware;
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

Route::middleware('auth:sanctum')->group(function () {
    // Common routes for all authenticated users
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(RoleMiddleware::class.':vendor')->group(function () {
        Route::apiResource('restaurants', RestaurantController::class);
        Route::post('/food-items/{restaurant}', [FoodItemController::class, 'store']);
        Route::apiResource('food-items', FoodItemController::class)->except(['store']);
    });

    Route::middleware(RoleMiddleware::class.':customer')->group(function () {
        Route::apiResource('orders', OrderController::class)->only(['store']);
        Route::apiResource('restaurants', RestaurantController::class)->only(['index', 'show']);
        Route::apiResource('food-items', FoodItemController::class)->only(['index', 'show']);
    });

    Route::middleware(RoleMiddleware::class.':admin')->group(function () {
        Route::get('users', AdminController::class, 'getUsers');
        Route::get('all-orders', AdminController::class, 'getAllOrders');
    });
});
