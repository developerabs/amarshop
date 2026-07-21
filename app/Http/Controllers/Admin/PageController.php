<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return view('admin.sections.pages.index');
    }
    public function search(Request $request)
    {
        $pages = Page::query();
        if ($request->has('query')) {
            $pages->whereRaw('LOWER(name) like ?', ['%' . strtolower($request->input('query')) . '%']);
        }
        $pages = $pages->paginate(10);
        return view('admin.components.data-table.pages-table', ['pages' => $pages]);
    }

    public function create()
    {
        return view('admin.sections.pages.create');
    }

    public function edit($page)
    {
        return view('admin.sections.pages.edit', compact('page'));
    }
}
