<?php

declare(strict_types=1);

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RiderController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/my-token', fn(Request $request) => $request->user())->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (): void {
    // Common routes for all authenticated users
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(RoleMiddleware::class.':vendor')->group(function (): void {
        Route::apiResource('restaurants', RestaurantController::class)->except(['index']);
        Route::post('/food-items/{restaurant}', [FoodItemController::class, 'store']);
        Route::apiResource('food-items', FoodItemController::class)->except(['store']);
    });

    Route::middleware(RoleMiddleware::class.':customer')->group(function (): void {
        Route::apiResource('orders', OrderController::class)->only(['store']);
        Route::apiResource('restaurants', RestaurantController::class)->only(['index', 'show']);
        Route::apiResource('food-items', FoodItemController::class)->only(['index', 'show']);
    });

    Route::middleware(RoleMiddleware::class.':admin')->group(function (): void {
        Route::get('users', [AdminController::class, 'getUsers']);
        Route::apiResource('restaurants', RestaurantController::class);
        Route::get('all-orders', [AdminController::class, 'getAllOrders']);
    });

    Route::middleware(RoleMiddleware::class.':rider')->group(function (): void {
        Route::get('/available-orders', [RiderController::class, 'getAvailableOrders']);
        Route::post('/accept-order/{orderId}', [RiderController::class, 'acceptOrder']);
        Route::put('/update-order-status/{orderId}', [RiderController::class, 'updateOrderStatus']);
        Route::get('/my-orders', [RiderController::class, 'getMyOrders']);
    });
});
