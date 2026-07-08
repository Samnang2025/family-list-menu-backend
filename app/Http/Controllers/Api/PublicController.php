<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function settings(): JsonResponse
    {
        $settings = Setting::current();

        return response()->json([
            'company_name' => $settings->company_name,
            'logo' => $settings->logo ? url('storage/'.$settings->logo) : null,
            'contact_number' => $settings->contact_number,
            'telegram_url' => $settings->telegram_url,
            'facebook_url' => $settings->facebook_url,
            'address' => $settings->address,
            'primary_color' => $settings->primary_color,
            'secondary_color' => $settings->secondary_color,
            'background_color' => $settings->background_color,
            'default_language' => $settings->default_language,
            'product_columns' => (int) $settings->product_columns,
        ]);
    }

    public function categories(): JsonResponse
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'products_count' => $category->products_count,
            ]);

        return response()->json($categories);
    }

    public function products(Request $request): JsonResponse
    {
        $query = Product::with('category')->orderBy('name');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('search')) {
            $search = '%'.$request->string('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        }

        $products = $query->get()->map(fn (Product $product) => $this->formatProduct($product));

        return response()->json($products);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->string('q')->trim();

        if ($search->isEmpty()) {
            return response()->json([]);
        }

        $products = Product::with('category')
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            })
            ->orderBy('name')
            ->limit(50)
            ->get()
            ->map(fn (Product $product) => $this->formatProduct($product));

        return response()->json($products);
    }

    private function formatProduct(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'image' => $product->image ? url('storage/'.$product->image) : null,
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->name,
            ],
        ];
    }
}
