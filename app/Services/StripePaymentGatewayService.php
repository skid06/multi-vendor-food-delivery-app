<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentGateway;
use Exception;
use Stripe\Stripe;

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
        } catch (Exception $e) {
            throw new Exception('Payment failed: '.$e->getMessage(), $e->getCode(), $e);
        }
    }
}
