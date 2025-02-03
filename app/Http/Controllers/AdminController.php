<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get users with pagination.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        // Set the number of items per page (default to 10 if not provided)
        $perPage = $request->input('per_page', 10);

        $users = User::paginate($perPage);

        return response()->json($users);
    }

    public function getAllOrders(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $orders = Order::paginate($perPage);

        return response()->json($orders);
    }
}
