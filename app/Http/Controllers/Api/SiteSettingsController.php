<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Admin\SiteSettings;
use Illuminate\Http\Request;

class SiteSettingsController extends Controller
{
    public function generalSettings()
    {
        $generalSettings = SiteSettings::where('group', 'general')->get()->pluck('value', 'key')->toArray();

        $data = [
            'site_name' => $generalSettings['site_name'] ?? null,
            'site_title' => $generalSettings['site_title'] ?? null,
            'site_description' => $generalSettings['site_description'] ?? null,
            'site_email' => $generalSettings['site_email'] ?? null,
            'site_phone' => $generalSettings['site_phone'] ?? null,
            'site_address' => $generalSettings['site_address'] ?? null,
            'free_shipping_amount' => $generalSettings['free_shipping_amount'] ?? null,
            'copyright_text' => $generalSettings['copyright_text'] ?? null,
            'site_logo' => $generalSettings['site_logo'] ? asset('storage/' . $generalSettings['site_logo']) : null,
            'site_favicon' => $generalSettings['site_favicon'] ? asset('storage/' . $generalSettings['site_favicon']) : null,
        ];

        return ApiResponse::success('General settings retrieved successfully.', $data);
    }
}
