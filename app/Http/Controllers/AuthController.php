<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register new User
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:6",
        ]);

        $user = User::create([
            "name" => $fields["name"],
            "email" => $fields["email"],
            "password" => Hash::make($fields["password"]),
        ]);

        $token = $user->createToken("authToken")->plainTextToken;
        $user["token"] = $token;

        return response()->json([
            "status_code" => 200,
            "data" => $user
        ], 200);
    }

    /**
     * Login User
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $fields = $request->validate([
            "email" => "required|string|email|max:255",
            "password" => "required|string|min:6",
        ]);

        // Check email
        $user = User::where("email", $fields["email"])->first();

        // Check Password
        if (!$user || !Hash::check($fields["password"], $user->password)) {
            return response()->json([
                "status_code" => 401,
                "message" => "Unauthorized"
            ], 401);
        }

        $token = $user->createToken("authToken")->plainTextToken;
        $user["token"] = $token;

        return response()->json([
            "status_code" => 200,
            "data" => $user
        ], 200);
    }

    /**
     * Logout User
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json([
            "status_code" => 200,
            "message" => "Success",
        ], 200);
    }
}
