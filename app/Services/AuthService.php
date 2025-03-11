<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login($data)
    {
        $user = User::where('email', $data['email'])->firstOrFail();
        if(!Hash::check($data['password'], $user->password)) {
            throw new \Exception('Неверные данные', 400);
        }
        $token = $user->createToken('my-app-token')->plainTextToken;
        $user->tokens()->where('token', hash('sha256', explode('|', $token)[1]))
            ->update(['expires_at' => now()->addDays(10)]);

        return ['token' => $token];
    }
    public function logout()
    {

    }
}
