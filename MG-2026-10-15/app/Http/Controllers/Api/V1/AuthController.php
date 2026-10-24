<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check(
            $data['password'],
            $user->password
        )) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken('lab-api')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }
}
