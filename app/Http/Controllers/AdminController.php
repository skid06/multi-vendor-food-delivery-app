<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get users with pagination.
     *
     * @return JsonResponse
     */
    public function getUsers(Request $request)
    {
        // Get the value of 'per_page' from the request and cast it to an integer
        $perPage = $request->input('per_page', 10);  // Default to 10 if not set
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 10;

        // Now pass $perPage to paginate, which is guaranteed to be an integer
        $users = User::paginate($perPage);

        return response()->json($users);
    }

    public function getAllOrders(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $perPage = filter_var($perPage, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 10;

        $orders = Order::paginate($perPage);

        return response()->json($orders);
    }
}
