<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentGateway;
use Exception;
use Stripe\Charge;
use Stripe\Stripe;

class StripePaymentGatewayService implements PaymentGateway
{
    public function __construct()
    {
        $stripeSecret = config('services.stripe.secret');
        if (!is_string($stripeSecret)) {
            throw new Exception('The Stripe API key is not a valid string.');
        }
        Stripe::setApiKey(strval($stripeSecret));
    }

    public function charge(float $amount, string $token): string
    {
        try {
            $charge = Charge::create([
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
