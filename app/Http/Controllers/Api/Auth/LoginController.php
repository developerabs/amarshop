<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return ApiResponse::error('Invalid credentials', [], 401);
        }

        $user = auth()->user();
        $token = $user->createToken('auth_token')->accessToken;

        return ApiResponse::success('Login successful', [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->token()->revoke();
            return ApiResponse::success('Logout successful');
        }
        return ApiResponse::error('User not authenticated', [], 401);
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate a 6-digit password reset code
        $code = rand(100000, 999999);
        $user->password_reset_code = $code;
        $user->save();

        // Send the password reset code via email
        try {
            Mail::to($user->email)->send(new PasswordResetCodeMail($code, $user));
            return ApiResponse::success('Password reset code sent to your email');
        } catch (Exception $e) {
            return ApiResponse::error('Failed to send email. Please try again later.', [], 500);
        }
    }
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password_reset_code' => 'required|digits:6',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->password_reset_code !== $request->password_reset_code) {
            return ApiResponse::error('Invalid password reset code', [], 400);
        }

        $user->password = bcrypt($request->new_password);
        $user->password_reset_code = null;
        $user->save();

        return ApiResponse::success('Password reset successful');
    }
}
