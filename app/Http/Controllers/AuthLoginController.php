<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password_hash)) {
            return response()->json([
                 'b1' => !$user,
                'b2' => Hash::check($request->password, $user->password_hash),
                'U' => $user,
                'password' => $request->password,
                'password-H' => Hash::make($request->password) ,
                'U-password' => $user->password_hash,
                'message' => 'Неверный email или пароль.',
            ], 422);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message'      => 'Успешный вход',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'role' => optional($user->role)->role_name,
        ]);
    }
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password_hash' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'message' => 'Регистрация успешна',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    function GetUserByToken(Request $request)
    {
        $user = $request->user()->loadMissing('role');
        return response()->json($user);
    }
}
