<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 400,
                'message' => 'Wrong email or password'
            ], 400);
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'user' => $user,
                'token' => $user->createToken($request->device_name)->plainTextToken
            ]
        ]);
    }
}
