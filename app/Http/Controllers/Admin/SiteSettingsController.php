<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SiteSettingsController extends Controller
{   
    public function generalSettings()
    {
        $generalSetting = SiteSettings::where('group', 'general')->get()->pluck('value', 'key')->toArray();
        return view('admin.sections.site-settings.general-settings', compact('generalSetting'));
    }

    public function generalSettingsUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:255',
            'site_email' => 'nullable|email|max:255',
            'site_phone' => 'nullable|string|max:20',
            'site_address' => 'nullable|string|max:255',
            'copyright_text' => 'nullable|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        foreach ($validatedData as $key => $value) {
            if (in_array($key, ['site_logo', 'site_favicon']) && $request->hasFile($key)) {
                $setting = SiteSettings::where('key', $key)->first();
                $value = updateImage($request->file($key), 'site-settings', $setting ? $setting->value : null);
                SiteSettings::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'group' => 'general', 'type' => 'string']
                );
                continue;
            }
            SiteSettings::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general', 'type' => 'string']
            );
        }

        return redirect()->back()->with('success', 'Site settings updated successfully.');
    }
}
