<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class JWTAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    private function getThrottleKey(): string
    {
        return Str::lower(request()->input('email')) . '|' . request()->ip();
    }

    private function checkRateLimit(): void
    {
        if (RateLimiter::tooManyAttempts($this->getThrottleKey(), 2)) {
            throw new TooManyRequestsHttpException(message: 'Too Many Attempts');
        };
    }

    public function login(): array
    {
        $this->checkRateLimit();

        request()->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request()->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            RateLimiter::hit($this->getThrottleKey());
            throw new AuthenticationException('Bad credentials');
        }

        return [
            'user' => Auth::user(),
            'token' => $token
        ];
    }

    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
