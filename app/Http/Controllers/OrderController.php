<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use App\Contracts\PaymentGateway;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
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

        // Process payment
        $paymentId = $this->paymentGateway->charge($totalAmount, $request->payment_token);

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $request->restaurant_id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // Add order items
        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_item_id' => $item['food_item_id'],
                'quantity' => $item['quantity'],
                'price' => FoodItem::find($item['food_item_id'])->price,
            ]);
        }

        return response()->json($order, 201);
    }
}
