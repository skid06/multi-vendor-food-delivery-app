<?php
namespace App\Services;

use Stripe\Stripe;
use App\Contracts\PaymentGateway;

class StripePaymentGatewayService implements PaymentGateway
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function charge(float $amount, string $token): string
    {
        try {
            $charge = \Stripe\Charge::create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => 'usd',
                'source' => $token,
            ]);

            return $charge->id;
        } catch (\Exception $e) {
            throw new \Exception("Payment failed: " . $e->getMessage());
        }
    }
}
