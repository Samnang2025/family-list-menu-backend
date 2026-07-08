<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug'])]
class Category extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            $category->slug = static::uniqueSlug(
                $category->slug ?: Str::slug($category->name)
            );
        });

        static::updating(function (Category $category) {
            if ($category->isDirty('name')) {
                $category->slug = static::uniqueSlug(
                    Str::slug($category->name),
                    $category->id
                );
            }
        });
    }

    public static function uniqueSlug(string $slug, ?int $exceptId = null): string
    {
        $base = $slug ?: 'category';
        $candidate = $base;
        $counter = 1;

        while (static::query()
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $base.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
