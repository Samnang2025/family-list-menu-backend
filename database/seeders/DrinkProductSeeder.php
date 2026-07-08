<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DrinkProductSeeder extends Seeder
{
    private const DRINK_CATEGORY_NAMES = ['ភេសជ្ជៈ', 'Drink', 'Drinks', 'Beverage', 'Beverages'];

    /** @var array<int, array{name: string, price: int, image: string}> */
    private array $drinks = [
        ['name' => 'កាហ្វេទឹកកក', 'price' => 3500, 'image' => 'https://images.unsplash.com/photo-1517701550927-30e673457a42?w=400&h=400&fit=crop&q=80'],
        ['name' => 'កូកាកូឡា', 'price' => 2000, 'image' => 'https://images.unsplash.com/photo-1629203851122-3726ecdf080e?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ប៉េបស៊ី', 'price' => 2000, 'image' => 'https://images.unsplash.com/photo-1544145949-f90425340c7e?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកដូងស្រស់', 'price' => 4000, 'image' => 'https://images.unsplash.com/photo-1558282268-d0108979603c?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកផ្លែម៉ាងូ', 'price' => 4500, 'image' => 'https://images.unsplash.com/photo-1600275669444-95825e6a4f68?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកផ្លែឪឡឹក', 'price' => 4000, 'image' => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=400&fit=crop&q=80'],
        ['name' => 'សម៉ូធីម៉ាងូ', 'price' => 5000, 'image' => 'https://images.unsplash.com/photo-1505252587541-3283ebd8f863?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកផ្លែផ្កាឈូក', 'price' => 4500, 'image' => 'https://images.unsplash.com/photo-1633004674876-4b8f4477d81f?w=400&h=400&fit=crop&q=80'],
        ['name' => 'តែក្រូចឆ្មារ', 'price' => 3000, 'image' => 'https://images.unsplash.com/photo-1564890369478-c89ca6d9cde9?w=400&h=400&fit=crop&q=80'],
        ['name' => 'តែទឹកឃ្មុំ', 'price' => 3000, 'image' => 'https://images.unsplash.com/photo-1597318184639-86c4d4d5a0a1?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកដោះគោម៉ាងូ', 'price' => 5000, 'image' => 'https://images.unsplash.com/photo-1572495614681-2c9633036072?w=400&h=400&fit=crop&q=80'],
        ['name' => 'សម៉ូធីស្ត្របឺរី', 'price' => 5500, 'image' => 'https://images.unsplash.com/photo-1553530666-8e0c7d3fb6af?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកផ្លែប៉ោម', 'price' => 4000, 'image' => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកផ្លែក្រូច', 'price' => 4000, 'image' => 'https://images.unsplash.com/photo-1621506270187-5c1ef2d639d1?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកក្រូចស', 'price' => 3500, 'image' => 'https://images.unsplash.com/photo-1621263765878-6d178e01bc29?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកសូដា', 'price' => 2500, 'image' => 'https://images.unsplash.com/photo-1581636621072-681e8607b306?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកផ្លែស្វាយ', 'price' => 4500, 'image' => 'https://images.unsplash.com/photo-1601342636314-5e4e1b6e6876?w=400&h=400&fit=crop&q=80'],
        ['name' => 'តែខ្មៅ', 'price' => 2500, 'image' => 'https://images.unsplash.com/photo-1556677183-21905102219b?w=400&h=400&fit=crop&q=80'],
        ['name' => 'តែបៃតង', 'price' => 2500, 'image' => 'https://images.unsplash.com/photo-1563822243828-92fc707dd023?w=400&h=400&fit=crop&q=80'],
        ['name' => 'តែទឹកដោះគោ', 'price' => 4000, 'image' => 'https://images.unsplash.com/photo-1525385133512-2f3bdd039054?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ប៊ឺប៊លទឹកកក', 'price' => 5500, 'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=400&fit=crop&q=80'],
        ['name' => 'សម៉ូធីចេក', 'price' => 4500, 'image' => 'https://images.unsplash.com/photo-1577007868186-43e205814352?w=400&h=400&fit=crop&q=80'],
        ['name' => 'សម៉ូធីអាវ៉ូកាដូ', 'price' => 6000, 'image' => 'https://images.unsplash.com/photo-1609501678108-5f957d0b2b1a?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកផ្លែផ្លែង', 'price' => 4000, 'image' => 'https://images.unsplash.com/photo-1622595192320-5d4d5b6e6771?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកឃ្មុំក្រូច', 'price' => 3500, 'image' => 'https://images.unsplash.com/photo-1622595469436-62c5d0a5f1e2?w=400&h=400&fit=crop&q=80'],
        ['name' => 'តែទឹកកក', 'price' => 3000, 'image' => 'https://images.unsplash.com/photo-1558164811-0abc3f59d5c1?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកផ្លែក្រូចពងក្រពា', 'price' => 5000, 'image' => 'https://images.unsplash.com/photo-1547519030-aac4bbd12622?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកផ្លែស្ពៃរតី', 'price' => 3500, 'image' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកផ្លែទឹកឃ្មុំល្អ', 'price' => 4000, 'image' => 'https://images.unsplash.com/photo-1613478223719-2ab1182a854e?w=400&h=400&fit=crop&q=80'],
        ['name' => 'ទឹកផ្លែចេកទឹកដោះគោ', 'price' => 4500, 'image' => 'https://images.unsplash.com/photo-1572495614681-2c9633036072?w=400&h=400&fit=crop&q=80'],
    ];

    public function run(): void
    {
        $category = Category::query()
            ->whereIn('name', self::DRINK_CATEGORY_NAMES)
            ->orWhere('slug', 'drinks')
            ->orWhere('slug', 'drink')
            ->first();

        if (! $category) {
            $category = Category::query()
                ->where('name', 'like', '%ភេសជ្ជ%')
                ->first();
        }

        if (! $category) {
            $this->command?->error('Drink category not found.');

            return;
        }

        $products = Product::query()
            ->where('category_id', $category->id)
            ->orderBy('id')
            ->get();

        if ($products->isEmpty()) {
            $this->command?->warn('No products in drink category. Creating new drink products...');
            foreach ($this->drinks as $drink) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $drink['name'],
                    'price' => $drink['price'],
                    'image' => $this->downloadImage($drink['image'], $drink['name']),
                ]);
            }

            return;
        }

        foreach ($products as $index => $product) {
            $drink = $this->drinks[$index % count($this->drinks)];

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->update([
                'name' => $drink['name'],
                'price' => $drink['price'],
                'image' => $this->downloadImage($drink['image'], $drink['name']),
            ]);
        }

        $this->command?->info("Updated {$products->count()} drink products in category: {$category->name}");
    }

    private function downloadImage(string $url, string $name): ?string
    {
        $response = Http::withOptions(['verify' => false])->timeout(30)->get($url);

        if ($response->successful()) {
            $slug = Str::slug($name) ?: 'drink';
            $filename = "products/{$slug}-".Str::random(8).'.jpg';
            Storage::disk('public')->put($filename, $response->body());

            return $filename;
        }

        return $this->generatePlaceholderImage($name);
    }

    private function generatePlaceholderImage(string $name): string
    {
        $width = 400;
        $height = 400;
        $image = imagecreatetruecolor($width, $height);

        $palette = [
            [22, 163, 74],
            [21, 128, 61],
            [74, 222, 128],
            [134, 239, 172],
            [5, 150, 105],
            [16, 185, 129],
        ];

        $base = $palette[crc32($name) % count($palette)];
        $bg = imagecolorallocate($image, $base[0], $base[1], $base[2]);
        imagefill($image, 0, 0, $bg);

        $overlay = imagecolorallocatealpha($image, 255, 255, 255, 90);
        imagefilledellipse($image, 200, 170, 220, 220, $overlay);

        $cup = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 150, 120, 250, 260, $cup);
        imagefilledellipse($image, 200, 120, 100, 30, $cup);

        $straw = imagecolorallocate($image, 240, 240, 240);
        imageline($image, 230, 80, 260, 140, $straw);

        $slug = Str::slug($name) ?: 'drink';
        $filename = "products/{$slug}-".Str::random(8).'.jpg';
        $fullPath = Storage::disk('public')->path($filename);

        if (! is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        imagejpeg($image, $fullPath, 85);
        imagedestroy($image);

        return $filename;
    }
}
