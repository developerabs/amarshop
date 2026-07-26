<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function index()
    {
        return view('admin.sections.faqs.index');
    }

    public function search(Request $request)
    {
        $query = Faq::query();

        if ($request->filled('query')) {
            $term = trim((string) $request->input('query'));
            $query->where(function ($q) use ($term) {
                $q->where('question', 'like', '%' . $term . '%')
                    ->orWhere('answer', 'like', '%' . $term . '%');
            });
        }

        $faqs = $query->orderBy('sort_order')->latest()->paginate(10)->withQueryString();

        return view('admin.components.data-table.faqs-table', compact('faqs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:5000',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'addModal');
        }

        $validated = $validator->validated();

        DB::transaction(function () use ($validated) {
            Faq::create([
                'question' => $validated['question'],
                'answer' => $validated['answer'],
                'sort_order' => $validated['sort_order'] ?? 0,
                'status' => (bool) ($validated['status'] ?? false),
            ]);
        });

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faq_id' => 'required|exists:faqs,id',
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:5000',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('modal', 'editModal');
        }

        $validated = $validator->validated();
        $faq = Faq::findOrFail($validated['faq_id']);

        DB::transaction(function () use ($validated, $faq) {
            $faq->update([
                'question' => $validated['question'],
                'answer' => $validated['answer'],
                'sort_order' => $validated['sort_order'] ?? 0,
                'status' => (bool) ($validated['status'] ?? false),
            ]);
        });

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }
}
