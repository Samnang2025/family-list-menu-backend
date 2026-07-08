<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];

    public int $imported = 0;

    public int $skipped = 0;

    public int $duplicates = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $line = $index + 2;

            $name = trim((string) ($row['product_name'] ?? $row['name'] ?? ''));
            $description = trim((string) ($row['description'] ?? ''));
            $categoryName = trim((string) ($row['category'] ?? ''));
            $price = $row['price'] ?? null;
            $imageUrl = trim((string) ($row['image_url'] ?? $row['image'] ?? ''));

            if ($name === '' && $categoryName === '' && $price === null) {
                continue;
            }

            if ($name === '') {
                $this->errors[] = "Row {$line}: Product name is required.";
                $this->skipped++;
                continue;
            }

            if ($categoryName === '') {
                $this->errors[] = "Row {$line}: Category is required.";
                $this->skipped++;
                continue;
            }

            if (! is_numeric($price) || (float) $price < 0) {
                $this->errors[] = "Row {$line}: Invalid price for '{$name}'.";
                $this->skipped++;
                continue;
            }

            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                ['slug' => Str::slug($categoryName)]
            );

            $exists = Product::where('name', $name)
                ->where('category_id', $category->id)
                ->exists();

            if ($exists) {
                $this->errors[] = "Row {$line}: Duplicate product '{$name}' in category '{$categoryName}'.";
                $this->duplicates++;
                $this->skipped++;
                continue;
            }

            $imagePath = null;
            if ($imageUrl !== '') {
                $imagePath = $this->downloadImage($imageUrl, $name, $line);
            }

            Product::create([
                'name' => $name,
                'description' => $description !== '' ? $description : null,
                'category_id' => $category->id,
                'price' => (float) $price,
                'image' => $imagePath,
            ]);

            $this->imported++;
        }
    }

    private function downloadImage(string $url, string $productName, int $line): ?string
    {
        try {
            $response = Http::timeout(15)->get($url);

            if (! $response->successful()) {
                $this->errors[] = "Row {$line}: Failed to download image for '{$productName}'.";

                return null;
            }

            $extension = pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION) ?: 'jpg';
            $filename = 'products/'.Str::slug($productName).'-'.uniqid().'.'.$extension;

            Storage::disk('public')->put($filename, $response->body());

            return $filename;
        } catch (\Throwable) {
            $this->errors[] = "Row {$line}: Failed to download image for '{$productName}'.";

            return null;
        }
    }
}
