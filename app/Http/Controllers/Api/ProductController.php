<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(): JsonResponse
    {
        $products = Product::all();

        return response()->json([
            'message' => 'Products retrieved successfully.',
            'data' => $products
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        return response()->json([
            'message' => 'Product retrieved successfully.',
            'data' => $product
        ]);
    }
}
