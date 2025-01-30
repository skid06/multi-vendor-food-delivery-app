<?php

use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('restaurants', RestaurantController::class);
    Route::apiResource('food-items', FoodItemController::class);
    Route::apiResource('orders', OrderController::class);
});
