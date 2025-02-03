<?php

namespace App\Services;

use App\Http\Requests\StoreOrderRequest;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Contracts\PaymentGateway;

class OrderService
{
    protected $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Handle the process of creating an order.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(StoreOrderRequest $request)
    {
        // Calculate total amount
        $totalAmount = $this->calculateTotalAmount($request->items);

        // Process the payment
        $paymentId = $this->paymentGateway->charge($totalAmount, $request->payment_token);

        // Create the order
        $order = $this->createOrderRecord($request, $totalAmount);

        // Add order items
        $this->addOrderItems($order, $request->items);

        return response()->json($order, 201);
    }

    /**
     * Calculate the total amount of the order.
     *
     * @param array $items
     * @return float
     */
    private function calculateTotalAmount(array $items)
    {
        $totalAmount = 0;

        foreach ($items as $item) {
            $foodItem = FoodItem::find($item['food_item_id']);
            $totalAmount += $foodItem->price * $item['quantity'];
        }

        return $totalAmount;
    }

    /**
     * Create the order record.
     *
     * @param Request $request
     * @param float $totalAmount
     * @return Order
     */
    private function createOrderRecord(Request $request, float $totalAmount)
    {
        return Order::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $request->restaurant_id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);
    }

    /**
     * Add the order items to the order.
     *
     * @param Order $order
     * @param array $items
     * @return void
     */
    private function addOrderItems(Order $order, array $items)
    {
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_item_id' => $item['food_item_id'],
                'quantity' => $item['quantity'],
                'price' => FoodItem::find($item['food_item_id'])->price,
            ]);
        }
    }
}
