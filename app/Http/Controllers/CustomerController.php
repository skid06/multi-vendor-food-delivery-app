<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Get all restaurants
     */
    public function getRestaurants() : JsonResponse
    {
        $restaurants = Restaurant::all();

        return response()->json($restaurants);
    }

    /**
     * Get food items for a specific restaurant
     *
     * @param int $restaurantId
     */
    public function getFoodItems($restaurantId) : JsonResponse
    {
        $foodItems = FoodItem::where('restaurant_id', $restaurantId)->get();

        return response()->json($foodItems);
    }

    /**
     * Place an order
     */
    public function placeOrder(StoreCustomerRequest $request) : JsonResponse
    {
        $validatedRequest = $request->validated();

        /** @var array<int, array{food_item_id: int, quantity: int}> $items */
        $items = $validatedRequest['items'];

        // Calculate total amount
        $totalAmount = 0;
        foreach ($items as $item) {
            $foodItem = FoodItem::findOrFail($item['food_item_id']);
            $totalAmount += $foodItem->price * $item['quantity'];
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $validatedRequest['restaurant_id'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // Add order items
        foreach ($items as $item) {
            $order->orderItems()->create([
                'food_item_id' => $item['food_item_id'],
                'quantity' => $item['quantity'],
                'price' => FoodItem::findOrFail($item['food_item_id'])->price,
            ]);
        }

        return response()->json($order, 201);
    }

    /**
     * Get order history for the customer
     */
    public function getOrderHistory() : JsonResponse
    {
        $orders = Order::where('user_id', auth()->id())->get();

        return response()->json($orders);
    }
}
