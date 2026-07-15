<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        return view('admin.sections.sliders.index');
    }
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);
        $sliders = Slider::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.components.data-table.sliders-table', compact('sliders'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|url|max:255',
        ]);
        $imagePath = $request->file('image')->store('sliders', 'public');
        Slider::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
            'button_text' => $request->input('button_text'),
            'button_link' => $request->input('button_link'),
        ]);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully.');
    }
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sliders,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|url|max:255',
        ]);
        $slider = Slider::findOrFail($request->input('id'));
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('sliders', 'public');
            $slider->image = $imagePath;
        }
        $slider->title = $request->input('title');
        $slider->description = $request->input('description');
        $slider->button_text = $request->input('button_text');
        $slider->button_link = $request->input('button_link');
        $slider->save();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully.');
    }
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted successfully.');
    }
}
