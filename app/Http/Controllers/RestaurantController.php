<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    public function index()
    {
        return Restaurant::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'logo' => 'nullable|string',
        ]);

        $restaurant = Restaurant::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'logo' => $request->logo,
        ]);

        return response()->json($restaurant, 201);
    }

    public function show(Restaurant $restaurant)
    {
        return $restaurant;
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $restaurant->update($request->all());
        return response()->json($restaurant, 200);
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return response()->json(null, 204);
    }
}
