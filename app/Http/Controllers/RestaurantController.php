<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestaurantRequest;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class RestaurantController extends Controller
{
    /**
     * @return Collection<int, Restaurant>
     */
    public function index() : Collection
    {
        return Restaurant::all();
    }

    /**
     * @param StoreRestaurantRequest $request
     * @return JsonResponse
     */
    public function store(StoreRestaurantRequest $request) : JsonResponse
    {
        $validatedRequest = $request->validated();

        $restaurant = Restaurant::create([
            'user_id' => Auth::id(),
            'name' => $validatedRequest['name'],
            'description' => $validatedRequest['description'],
            'address' => $validatedRequest['address'],
            'phone' => $validatedRequest['phone'],
            'logo' => $validatedRequest['logo'],
        ]);

        return response()->json($restaurant, 201);
    }

    /**
     * @param Restaurant $restaurant
     * @return Restaurant
     */
    public function show(Restaurant $restaurant): Restaurant
    {
        return $restaurant;
    }

    /**
     * @param StoreRestaurantRequest $request
     * @param Restaurant $restaurant
     * @return JsonResponse
     */
    public function update(StoreRestaurantRequest $request, Restaurant $restaurant) : JsonResponse
    {
        $restaurant->update($request->validated());

        return response()->json($restaurant, 200);
    }

    /**
     * @param Restaurant $restaurant
     * @return JsonResponse
     */
    public function destroy(Restaurant $restaurant) : JsonResponse
    {
        $restaurant->delete();

        return response()->json(null, 204);
    }
}
