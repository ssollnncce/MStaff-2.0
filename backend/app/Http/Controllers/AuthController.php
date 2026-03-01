<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     // Registration logic here
    // }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'The provided email or password are incorrect'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_data' => $user
        ]);
    }

    public function forgotPassword(Request $request)
    {
        // Forgot password logic here
    }

    public function resetPassword(Request $request)
    {
        // Reset password logic here
    }

    public function logout(Request $request)
    {
        // Logout logic here
    }
    public function userInfo() {
        $user = Auth::user();
        $employee = $user->employee;

        return response()->json([
            'message' => 'User information retrieved successfully',
            'data' => [
                'user' => $user,
                'employee' => $employee
            ]
        ]);
    }
}
