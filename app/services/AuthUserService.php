<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;

class AuthUserService
{
    /**
     * @param array $credentials
     * 
     * @return bool
     */
    public function login($credentials) //:bool
    {
    }

    /**
     * @return bool
     */
    public function logout() //:bool
    {
    }

    /**
     * @param array $userPayload
     * 
     * @return bool
     */
    public function register(array $userPayload) //:User
    {
        $user = User::create($userPayload);

        event(new Registered($user));
    }
}
