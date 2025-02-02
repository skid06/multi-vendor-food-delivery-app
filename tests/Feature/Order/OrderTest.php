<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\FoodItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Stripe;
use Stripe\Charge;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_place_order()
    {
        // Create a user
        $user = User::factory()->create(['role' => 'customer']);
        $token = $user->createToken('authToken')->plainTextToken;

        // Create a restaurant
        $restaurant = Restaurant::factory()->create();

        // Create a food item
        $foodItem = FoodItem::factory()->create(['restaurant_id' => $restaurant->id]);

        // Mock the Stripe Charge class
        $this->mockStripeCharge();

        // Make the API request to place an order
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/orders', [
            'restaurant_id' => $restaurant->id,
            'items' => [
                ['food_item_id' => $foodItem->id, 'quantity' => 2],
            ],
            'payment_token' => 'tok_visa', // Stripe test token
        ]);

        // Assert the response
        $response->assertStatus(201)
            ->assertJson(['status' => 'pending']);

        // Assert the order was created in the database
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'restaurant_id' => $restaurant->id,
            'total_amount' => $foodItem->price * 2,
        ]);
    }

    protected function mockStripeCharge()
    {
        // Mock the Stripe\Charge class
        $mockCharge = \Mockery::mock('overload:Stripe\Charge');
        $mockCharge->shouldReceive('create')
            ->once()
            ->with([
                'amount' => 2000, // Example amount in cents
                'currency' => 'usd',
                'source' => 'tok_visa', // Test token
            ])
            ->andReturn((object) [
                'id' => 'ch_12345', // Mock charge ID
                'status' => 'succeeded',
            ]);

        // Bind the mock to the Stripe facade
        $this->app->instance(Charge::class, $mockCharge);
    }
}
