<?php

declare(strict_types=1);

namespace Tests\Feature\Restaurant;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_restaurant()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['role' => 'vendor']);
        $token = $user->createToken('authToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/restaurants', [
            'name' => 'Test Restaurant',
            'description' => 'A test restaurant',
            'address' => '123 Test St',
            'phone' => '1234567890',
        ]);

        $response->assertStatus(201)
            ->assertJson(['name' => 'Test Restaurant']);

        $this->assertDatabaseHas('restaurants', [
            'name' => 'Test Restaurant',
        ]);
    }

    public function test_update_restaurant()
    {
        $user = User::factory()->create(['role' => 'vendor']);
        $restaurant = Restaurant::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('authToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->putJson("/api/restaurants/{$restaurant->id}", [
            'name' => 'Updated Restaurant',
        ]);

        $response->assertStatus(200)
            ->assertJson(['name' => 'Updated Restaurant']);

        $this->assertDatabaseHas('restaurants', [
            'name' => 'Updated Restaurant',
        ]);
    }

    public function test_delete_restaurant()
    {
        $user = User::factory()->create(['role' => 'vendor']);
        $restaurant = Restaurant::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('authToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->deleteJson("/api/restaurants/{$restaurant->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('restaurants', [
            'id' => $restaurant->id,
        ]);
    }
}
