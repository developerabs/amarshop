<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserCareController extends Controller
{
    public function index()
    {
        return view('admin.sections.users.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $searchTerm = $request->input('query');

        $users = User::query()
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.components.data-table.users-table', compact('users', 'searchTerm'));
    }

    public function store(Request $request)
    {
        // Implement user creation functionality
    }

    public function update(Request $request)
    {
        // Implement user update functionality
    }

    public function destroy($userId)
    {
        // Implement user deletion functionality
    }
}
