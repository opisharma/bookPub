<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function register(Request $request) {
        $request->validate([
           "name" => "required|string",
           "email" => "required|string|email|unique:users",
           "password" => "required|confirmed"
        ]);

        User::create([

            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        return response()->json([

            "status" => true,
            "message" => "Registration Success",
            "data" => []
        ]);
    }
    public function login(Request $request) {

        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $token = Auth::attempt([

            "email" => $request->email,
            "password" => $request-> password
        ]);

        if(!$token){
            return response()->json([
                "status" => false,
                "message" => "Unauthorized login"
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "Login successful",
            "token" => $token
        ]);
    }

    public function logout(Request $request) {
        Auth::invalidate(Auth::parseToken());

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    // public function profile() {}
    // public function refreshToken() {}
    // public function logout() {}
}
