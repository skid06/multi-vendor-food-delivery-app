<?php

declare(strict_types=1);

namespace Tests\Feature\FoodItem;

use App\Models\FoodItem;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_food_item()
    {
        //        $this->withoutExceptionHandling();
        $user = User::factory()->create(['role' => 'vendor']);
        $restaurant = Restaurant::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('authToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson("/api/food-items/{$restaurant->id}", [
            'name' => 'Test Food',
            'description' => 'A test food item',
            'price' => 10.99,
        ]);

        $response->assertStatus(201)
            ->assertJson(['name' => 'Test Food']);

        $this->assertDatabaseHas('food_items', [
            'name' => 'Test Food',
        ]);
    }

    public function test_update_food_item()
    {
        $user = User::factory()->create(['role' => 'vendor']);
        $restaurant = Restaurant::factory()->create(['user_id' => $user->id]);
        $foodItem = FoodItem::factory()->create(['restaurant_id' => $restaurant->id]);
        $token = $user->createToken('authToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->putJson("/api/food-items/{$foodItem->id}", [
            'name' => 'Updated Food',
        ]);

        $response->assertStatus(200)
            ->assertJson(['name' => 'Updated Food']);

        $this->assertDatabaseHas('food_items', [
            'name' => 'Updated Food',
        ]);
    }
}
