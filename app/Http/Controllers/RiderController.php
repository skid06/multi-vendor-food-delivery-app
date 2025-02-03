<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rider;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    // Get available orders for riders
    public function getAvailableOrders()
    {
        $orders = Order::where('status', 'ready_for_delivery')->get();
        return response()->json($orders);
    }

    // Accept an order
    public function acceptOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Check if the order is available for delivery
        if ($order->status !== 'ready_for_delivery') {
            return response()->json(['error' => 'Order is not available for delivery'], 400);
        }

        // Assign the order to the rider
        $order->update([
            'rider_id' => auth()->id(),
            'status' => 'out_for_delivery',
        ]);

        return response()->json($order);
    }

    // Update order status (e.g., delivered)
    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:out_for_delivery,delivered',
        ]);

        $order = Order::findOrFail($orderId);

        // Ensure the rider can only update their own orders
        if ($order->rider_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order->update(['status' => $request->status]);

        return response()->json($order);
    }

    // Get orders assigned to the rider
    public function getMyOrders()
    {
        $orders = Order::where('rider_id', auth()->id())->get();
        return response()->json($orders);
    }
}
