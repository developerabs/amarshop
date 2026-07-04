<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\VariantType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VariantTypesController extends Controller
{
    public function index()
    {
        $variantTypes = VariantType::orderBy('id', 'desc')->paginate(20);
        return view('admin.sections.variant-types.index', compact('variantTypes'));
    }

    public function search(Request $request)
    {
        $query = VariantType::query();

        if ($request->has('name') && !empty($request->input('name'))) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('status', $request->input('status'));
        }

        $variantTypes = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return view('admin.components.data-table.variant-types-table', compact('variantTypes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal','addModal');
        }

        VariantType::create($request->only('name'));
        return redirect()->route('admin.variant-types.index')->with('success', 'Variant type created successfully.');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'variant_type_id' => 'required|exists:variant_types,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal','editModal');
        }

        $variantType = VariantType::findOrFail($request->variant_type_id);
        $variantType->update($request->only('name'));

        return redirect()->route('admin.variant-types.index')->with('success', 'Variant type updated successfully.');
    }

    public function destroy($variantTypeId)
    {
        $variantType = VariantType::findOrFail($variantTypeId);
        $variantType->delete();

        return redirect()->route('admin.variant-types.index')->with('success', 'Variant type deleted successfully.');
    }
}
