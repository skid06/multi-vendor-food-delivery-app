<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $paymentGateway;

    protected $orderService;

    public function __construct(PaymentGateway $paymentGateway, OrderService $orderService)
    {
        $this->paymentGateway = $paymentGateway;
        $this->orderService = $orderService;
    }

    public function store(StoreOrderRequest $request)
    {
        return $this->orderService->createOrder($request);
    }
}
