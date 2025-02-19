<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\FoodItem;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**Get orders for the vendor's restaurants
     *
     * @return JsonResponse
     */
    public function getOrders() : JsonResponse
    {
        $orders = Order::whereHas('restaurant', function (Builder $query) {
            $query->where('user_id', auth()->id());
        })->get();

        return response()->json($orders);
    }

    /**
     * Update order status
     *
     * @param Request $request
     * @param int $orderId
     * @return JsonResponse
     */
    public function updateOrderStatus(Request $request, $orderId) : JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($orderId);
        $order->update(['status' => $request->status]);

        return response()->json($order);
    }
}
