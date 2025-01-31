<?php
namespace App\Services;

use App\Contracts\PaymentGateway;

class PayPalPaymentGatewayService implements PaymentGateway
{
    public function charge($amount, $token)
    {
        // Implement PayPal payment logic here
        // Example: Use PayPal SDK to process payment
        return 'paypal_transaction_id';
    }
}
