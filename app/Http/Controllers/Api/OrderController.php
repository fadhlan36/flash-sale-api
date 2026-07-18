<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {

            $order = $this->orderService->checkout(
                $request->validated()['items']
            );

            return response()->json([
                'message' => 'Order created successfully.',
                'data' => $order
            ], 201);
        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 409);
        }
    }
}
