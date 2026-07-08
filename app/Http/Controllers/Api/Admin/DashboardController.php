<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'recent_products' => Product::with('category')
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn (Product $product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'category' => $product->category->name,
                    'created_at' => $product->created_at,
                ]),
        ]);
    }
}
