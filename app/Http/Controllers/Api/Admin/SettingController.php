<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function show(): JsonResponse
    {
        $settings = Setting::current();

        return response()->json([
            'company_name' => $settings->company_name,
            'logo' => $settings->logo ? url('storage/'.$settings->logo) : null,
            'logo_path' => $settings->logo,
            'contact_number' => $settings->contact_number,
            'telegram_url' => $settings->telegram_url,
            'facebook_url' => $settings->facebook_url,
            'address' => $settings->address,
            'primary_color' => $settings->primary_color,
            'secondary_color' => $settings->secondary_color,
            'background_color' => $settings->background_color,
            'default_language' => $settings->default_language,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'company_name' => ['sometimes', 'required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'telegram_url' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'primary_color' => ['sometimes', 'required', 'string', 'max:20'],
            'secondary_color' => ['sometimes', 'required', 'string', 'max:20'],
            'background_color' => ['sometimes', 'required', 'string', 'max:20'],
            'default_language' => ['sometimes', 'required', 'string', 'max:20'],
            'logo' => ['nullable', 'image', 'max:5120'],
            'remove_logo' => ['sometimes', 'boolean'],
        ]);

        $settings = Setting::current();

        if ($request->boolean('remove_logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $data['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $settings->update($data);

        return response()->json([
            'company_name' => $settings->company_name,
            'logo' => $settings->logo ? url('storage/'.$settings->logo) : null,
            'logo_path' => $settings->logo,
            'contact_number' => $settings->contact_number,
            'telegram_url' => $settings->telegram_url,
            'facebook_url' => $settings->facebook_url,
            'address' => $settings->address,
            'primary_color' => $settings->primary_color,
            'secondary_color' => $settings->secondary_color,
            'background_color' => $settings->background_color,
            'default_language' => $settings->default_language,
        ]);
    }
}
