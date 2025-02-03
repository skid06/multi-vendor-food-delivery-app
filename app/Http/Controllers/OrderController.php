<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use App\Contracts\PaymentGateway;

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
