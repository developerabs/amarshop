<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\ShippingCharges;

class ShippingChargeController extends Controller
{
    public function index()
    {
        $shippingCharges = ShippingCharges::all();
        return view('admin.sections.shipping-charges.index', compact('shippingCharges'));
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
}
