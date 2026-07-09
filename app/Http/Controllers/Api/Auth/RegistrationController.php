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
                $user = User::create([
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                    'email' => $validatedData['email'],
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
