<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(): array
    {
        request()->validate([
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string',
        ]);

        $credentials = request()->only('email', 'password');

        $token = auth()->attempt($credentials);

        if (!$token) {
            # code...
        }

        return [
            'user' => auth()->user(),
            'token' => $token
        ];
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
