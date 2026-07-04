<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::orderBy('id', 'desc')->paginate(20);
        return view('admin.sections.attributes.index', compact('attributes'));
    }
    public function search(Request $request)
    {
        // Implement search logic for attributes here
    }
    public function store(Request $request)
    {
        // Implement store logic for attributes here
    }
    public function update(Request $request)
    {
        // Implement update logic for attributes here
    }
    public function destroy($attributeId)
    {
        // Implement destroy logic for attributes here
        //
    }
}
