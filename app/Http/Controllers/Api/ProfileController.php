<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->headers->all());
        $user = $request->user();
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'image' => $user->image ? getImageUrl($user->image) : null,
        ];
        $data = [
            'user' => $userData,
        ];
        return ApiResponse::success('User profile retrieved successfully', $data);
    }

    public function update(Request $request)
    {
        dd($request->header('Authorization'));
        $user = $request->user();

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validatedData);

        $userData = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
        ];

        $data = [
            'user' => $userData,
        ];
        return ApiResponse::success('Profile updated successfully', $data);
    }
}
