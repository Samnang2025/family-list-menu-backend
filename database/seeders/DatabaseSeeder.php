<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@emenu.local',
            'password' => 'password',
            'role' => 'administrator',
        ]);

        Setting::create([
            'company_name' => 'Khmer Kitchen',
            'contact_number' => '+855 12 345 678',
            'telegram_url' => 'https://t.me/khmerkitchen',
            'facebook_url' => 'https://facebook.com/khmerkitchen',
            'address' => 'Phnom Penh, Cambodia',
            'primary_color' => '#16a34a',
            'secondary_color' => '#15803d',
            'background_color' => '#ffffff',
            'default_language' => 'khmer',
        ]);

        $categories = [
            'Appetizers' => [
                ['name' => 'Spring Rolls', 'price' => 3500],
                ['name' => 'Fish Amok Bites', 'price' => 4000],
            ],
            'Main Dishes' => [
                ['name' => 'Beef Lok Lak', 'price' => 7500],
                ['name' => 'Chicken Curry', 'price' => 6500],
                ['name' => 'Grilled Fish', 'price' => 8000],
            ],
            'Beverages' => [
                ['name' => 'Iced Coffee', 'price' => 2000],
                ['name' => 'Fresh Coconut', 'price' => 2500],
                ['name' => 'Mango Smoothie', 'price' => 3000],
            ],
            'Desserts' => [
                ['name' => 'Mango Sticky Rice', 'price' => 4500],
                ['name' => 'Coconut Jelly', 'price' => 2500],
            ],
        ];

        foreach ($categories as $categoryName => $products) {
            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);

            foreach ($products as $product) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                ]);
            }
        }
    }
}
