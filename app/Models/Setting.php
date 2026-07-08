<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'company_name',
    'logo',
    'contact_number',
    'telegram_url',
    'facebook_url',
    'address',
    'primary_color',
    'secondary_color',
    'background_color',
    'default_language',
])]
class Setting extends Model
{
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'company_name' => 'E-Menu Restaurant',
            'primary_color' => '#16a34a',
            'secondary_color' => '#15803d',
            'background_color' => '#ffffff',
            'default_language' => 'khmer',
        ]);
    }
}
