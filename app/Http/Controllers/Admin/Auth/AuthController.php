<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            if (auth()->user()->role === 'superadmin') {
                return redirect()->route('admin.dashboard');
            }
            auth()->logout();
            return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('admin.login');
    }
}
