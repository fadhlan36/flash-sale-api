<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    public function checkout(array $items): Order
    {
        return DB::transaction(function () use ($items) {

            $totalPrice = 0;

            // Simpan data product sementara
            $products = [];

            foreach ($items as $item) {

                $product = Product::where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new Exception("Product not found.");
                }

                if ($product->stock < $item['quantity']) {
                    throw new Exception("Insufficient stock for {$product->name}");
                }

                $product->stock -= $item['quantity'];
                $product->save();

                $subtotal = $product->price * $item['quantity'];

                $totalPrice += $subtotal;

                $products[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ];
            }

            $order = Order::create([
                'total_price' => $totalPrice,
                'status' => 'completed'
            ]);

            foreach ($products as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            return $order;
        });
    }
}
