<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class FoodItemController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        return $restaurant->foodItems;
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|string',
        ]);

        $foodItem = $restaurant->foodItems()->create($request->all());

        return response()->json($foodItem, 201);
    }

    public function show(FoodItem $foodItem)
    {
        return $foodItem;
    }

    public function update(Request $request, FoodItem $foodItem)
    {
        $foodItem->update($request->all());

        return response()->json($foodItem, 200);
    }

    public function destroy(FoodItem $foodItem)
    {
        $foodItem->delete();

        return response()->json(null, 204);
    }
}
