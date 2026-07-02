<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\SiteSettings;

class SiteSettingsController extends Controller
{
    public function index()
    {
        $siteSettings = SiteSettings::first();

        $data = [
            'site_name' => $siteSettings->site_name ?? null,
            'site_title' => $siteSettings->site_title ?? null,
            'site_description' => $siteSettings->site_description ?? null,
            'site_email' => $siteSettings->site_email ?? null,
            'site_phone' => $siteSettings->site_phone ?? null,
            'site_address' => $siteSettings->site_address ?? null,
            'copyright_text' => $siteSettings->copyright_text ?? null,
            'site_logo' => $siteSettings->site_logo ? asset('storage/' . $siteSettings->site_logo) : null,
            'site_favicon' => $siteSettings->site_favicon ? asset('storage/' . $siteSettings->site_favicon) : null,
        ];

        return response()->json($data);
    }
}
