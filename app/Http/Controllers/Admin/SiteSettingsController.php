<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SiteSettingsController extends Controller
{   
    public function index()
    {
        $siteSetting = SiteSettings::first();
        return view('admin.sections.site-settings.index', compact('siteSetting'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:255',
            'site_email' => 'required|email|max:255',
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

        $siteSetting = SiteSettings::first();

        $data = $request->except(['site_logo', 'site_favicon']);

        if ($request->hasFile('site_logo')) {
            if ($siteSetting && $siteSetting->site_logo) {
                Storage::disk('public')->delete($siteSetting->site_logo);
            }
            $data['site_logo'] = $request->file('site_logo')->store('site-settings', 'public');
        }

        if ($request->hasFile('site_favicon')) {
            if ($siteSetting && $siteSetting->site_favicon) {
                Storage::disk('public')->delete($siteSetting->site_favicon);
            }
            $data['site_favicon'] = $request->file('site_favicon')->store('site-settings', 'public');
        }

        if ($siteSetting) {
            $siteSetting->update($data);
        } else {
            SiteSettings::create($data);
        }

        return redirect()->back()->with('success', 'Site settings updated successfully.');
    }
}
