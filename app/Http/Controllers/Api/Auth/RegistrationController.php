<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('Registration validation failed', $validator->errors()->all(), 422);
        }

        $validatedData = $validator->validated();

        $existingUser = User::where('email', $validatedData['email'])->first();
        if ($existingUser) {
            return ApiResponse::error('Email already exists.', 409);
        }

        try {
            DB::transaction(function () use ($validatedData, &$token) {
                $baseUsername = strtolower(preg_replace('/[^a-z0-9]/', '', $validatedData['first_name'].$validatedData['last_name']));
                $username = $baseUsername;
                $counter = 1;
                
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }
                
                $user = User::create([
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                    'username' => $username,
                    'email' => $validatedData['email'],
                    'phone' => $validatedData['phone'] ?? null,
                    'address' => $validatedData['address'] ?? null,
                    'password' => bcrypt($validatedData['password']),
                ]);

                $token = $user->createToken('auth_token')->accessToken;
            });
            return ApiResponse::success('Registration successful', [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error('Registration failed', $e->getMessage(), 500);
        }
    }
}
