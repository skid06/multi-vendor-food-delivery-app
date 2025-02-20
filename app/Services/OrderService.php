<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Http\Requests\StoreOrderRequest;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function __construct(protected PaymentGateway $paymentGateway)
    {
    }

    /**
     * Handle the process of creating an order.
     */
    public function createOrder(StoreOrderRequest $request): JsonResponse
    {
        /**
         * @var array<int, array{food_item_id: int, quantity: int}> $items
         */
        $items = $request->validated()['items'];
        // Calculate total amount
        $totalAmount = $this->calculateTotalAmount($items);

        /**
         * @var string $payment_token
         */
        $payment_token = $request->validated()['payment_token'];
        // Process the payment
        $this->paymentGateway->charge($totalAmount, $payment_token);

        // Create the order
        $order = $this->createOrderRecord($request, $totalAmount);

        // Add order items
        $this->addOrderItems($order, $items);

        return response()->json($order, 201);
    }

    /**
     * Calculate the total amount of the order.
     * @param array<int, array{food_item_id: int, quantity: int}> $items
     */
    private function calculateTotalAmount(array $items): int|float
    {
        $totalAmount = 0;

        foreach ($items as $item) {
            $foodItem = FoodItem::findOrFail($item['food_item_id']);
            $totalAmount += $foodItem->price * $item['quantity'];
        }

        return $totalAmount;
    }

    /**
     * Create the order record.
     *
     * @return Order
     */
    private function createOrderRecord(StoreOrderRequest $request, float $totalAmount)
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
     * @param array<int, array{food_item_id: int, quantity: int}> $items
     */
    private function addOrderItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_item_id' => $item['food_item_id'],
                'quantity' => $item['quantity'],
                'price' => FoodItem::findOrFail($item['food_item_id'])->price,
            ]);
        }
    }
}
