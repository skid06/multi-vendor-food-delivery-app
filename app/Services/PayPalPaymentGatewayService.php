<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentGateway;

class PayPalPaymentGatewayService implements PaymentGateway
{
    public function charge(float $amount, string $token): string
    {
        // Implement PayPal payment logic here
        // Example: Use PayPal SDK to process payment
        return 'paypal_transaction_id';
    }
}
