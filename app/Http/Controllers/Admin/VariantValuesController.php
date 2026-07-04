<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\VariantType;
use App\Models\Admin\VariantValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VariantValuesController extends Controller
{
    public function index()
    {
        $variantTypes = VariantType::orderBy('id', 'desc')->get();
        return view('admin.sections.variant-values.index', compact('variantTypes'));
    }
    public function search(Request $request) {
        
        $query = VariantValue::query();

        if ($request->has('query') && !empty($request->input('query'))) {
            $query->where('name', 'like', '%' . $request->input('query') . '%');
        }

        $variantValues = $query->with('variantType')->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return view('admin.components.data-table.variant-values-table', compact('variantValues'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'variant_type' => 'required|exists:variant_types,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal','addModal');
        }
        $validatedData = $validator->validated();


        VariantValue::create([
            'name' => $validatedData['name'],
            'variant_type_id' => $validatedData['variant_type'],
        ]);

        return redirect()->route('admin.variant-values.index')->with('success', 'Variant value created successfully.');
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'variant_value_id' => 'required|exists:variant_values,id',
            'name' => 'required|string|max:255',
            'variant_type' => 'required|exists:variant_types,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal','editModal');
        }

        $validatedData = $validator->validated();

        $variantValue = VariantValue::findOrFail($validatedData['variant_value_id']);
        $variantValue->update([
            'name' => $validatedData['name'],
            'variant_type_id' => $validatedData['variant_type'],
        ]);

        return redirect()->route('admin.variant-values.index')->with('success', 'Variant value updated successfully.');
    }
    public function destroy($variantValueId)
    {
        $variantValue = VariantValue::findOrFail($variantValueId);
        $variantValue->delete();
        return redirect()->route('admin.variant-values.index')->with('success', 'Variant value deleted successfully.');
    }
}
