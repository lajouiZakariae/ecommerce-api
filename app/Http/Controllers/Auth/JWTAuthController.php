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

    /**
     * Get the rate limiter's key
     *
     * @return string
     * 
     */
    private function getThrottleKey(): string
    {
        return Str::lower(request()->input('email')) . '|' . request()->ip();
    }

    /**
     * Check if the rate limiter reached the max attempts
     *
     * @return void
     * @throws TooManyRequestsHttpException
     */
    private function checkRateLimit(): void
    {
        if (RateLimiter::tooManyAttempts($this->getThrottleKey(), env('MAX_LOGIN_ATTEMPTS', 5))) {
            throw new TooManyRequestsHttpException(message: 'Too Many Attempts');
        };
    }

    /**
     * Authenticating user using JWT 
     *
     * @return array<string,mixed>
     * 
     */
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

    /**
     * Logging user out
     *
     * @return JsonResponse
     * 
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Refresh the JWT token
     *
     * @return JsonResponse
     * 
     */
    public function refresh(): JsonResponse
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
