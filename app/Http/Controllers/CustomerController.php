<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Get all restaurants
    public function getRestaurants()
    {
        $restaurants = Restaurant::all();

        return response()->json($restaurants);
    }

    // Get food items for a specific restaurant
    public function getFoodItems($restaurantId)
    {
        $foodItems = FoodItem::where('restaurant_id', $restaurantId)->get();

        return response()->json($foodItems);
    }

    // Place an order
    public function placeOrder(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'items' => 'required|array',
            'items.*.food_item_id' => 'required|exists:food_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_token' => 'required|string',
        ]);

        // Calculate total amount
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $foodItem = FoodItem::find($item['food_item_id']);
            $totalAmount += $foodItem->price * $item['quantity'];
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $request->restaurant_id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // Add order items
        foreach ($request->items as $item) {
            $order->orderItems()->create([
                'food_item_id' => $item['food_item_id'],
                'quantity' => $item['quantity'],
                'price' => FoodItem::find($item['food_item_id'])->price,
            ]);
        }

        return response()->json($order, 201);
    }

    // Get order history for the customer
    public function getOrderHistory()
    {
        $orders = Order::where('user_id', auth()->id())->get();

        return response()->json($orders);
    }
}
