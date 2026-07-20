<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ShippingCharges;
use App\Models\Admin\SiteSettings;
use Illuminate\Http\Request;

class ShippingChargeController extends Controller
{
    public function index()
    {
        $shippingCharges = ShippingCharges::all();
        $setting = SiteSettings::where('key', 'free_shipping_amount')->first();
        return view('admin.sections.shipping-charges.index', compact('shippingCharges', 'setting'));
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $shippingCharges = ShippingCharges::where('name', 'like', "%{$query}%")->latest()->paginate(10);
        return view('admin.components.data-table.shipping-charges-table', compact('shippingCharges'))->render();
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'charge' => 'required|numeric|min:0',
        ]);

        ShippingCharges::create($request->only('name', 'charge'));

        return redirect()->back()->with('success', 'Shipping charge created successfully.');
    }
    public function update(Request $request)
    {
        $shippingCharge = ShippingCharges::findOrFail($request->input('shipping_charge_id'));
        $request->validate([
            'shipping_charge_id' => 'required|exists:shipping_charges,id',
            'name' => 'required|string|max:100',
            'charge' => 'required|numeric|min:0',
        ]);

        $shippingCharge->update($request->only('name', 'charge'));

        return redirect()->back()->with('success', 'Shipping charge updated successfully.');
    }

    public function destroy(ShippingCharges $shippingCharge)
    {
        $shippingCharge->delete();
        return redirect()->back()->with('success', 'Shipping charge deleted successfully.');
    }
    public function updateFreeShippingAmount(Request $request)
    {
        $request->validate([
            'free_shipping_amount' => 'required|numeric|min:0',
        ]);

        $setting = SiteSettings::updateOrCreate(
            ['key' => 'free_shipping_amount'],
            ['value' => $request->input('free_shipping_amount')]
        );

        return redirect()->back()->with('success', 'Free shipping amount updated successfully.');
    }
}
