<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(protected PaymentGateway $paymentGateway, protected \App\Services\OrderService $orderService)
    {
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        return $this->orderService->createOrder($request);
    }
}
