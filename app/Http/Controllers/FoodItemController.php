<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreFoodItemRequest;
use App\Models\FoodItem;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class FoodItemController extends Controller
{
    /**
     * @param Restaurant $restaurant
     * @return Collection<int, FoodItem>
     */
    public function index(Restaurant $restaurant) : Collection
    {
        return $restaurant->foodItems;
    }

    /**
     * @param StoreFoodItemRequest $request
     * @param Restaurant $restaurant
     * @return JsonResponse
     */
    public function store(StoreFoodItemRequest $request, Restaurant $restaurant) : JsonResponse
    {
        $foodItem = $restaurant->foodItems()->create($request->validated());

        return response()->json($foodItem, 201);
    }

    public function show(FoodItem $foodItem): FoodItem
    {
        return $foodItem;
    }

    /**
     * @param StoreFoodItemRequest $request
     * @param FoodItem $foodItem
     * @return JsonResponse
     */
    public function update(StoreFoodItemRequest $request, FoodItem $foodItem) : JsonResponse
    {
        $foodItem->update($request->validated());

        return response()->json($foodItem, 200);
    }

    /**
     * @param FoodItem $foodItem
     * @return JsonResponse
     */
    public function destroy(FoodItem $foodItem) : JsonResponse
    {
        $foodItem->delete();

        return response()->json(null, 204);
    }
}
