<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(private UserRepository $users) {}

    public function login(string $email, string $password): array
    {
        $user = $this->users->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        // Block inactive users from logging in.
        if (!$user->active) {
            throw ValidationException::withMessages([
                'email' => ['User is inactive.'],
            ]);
        }

        $token = $user->createToken('api')->plainTextToken;
        return ['token' => $token, 'user' => $user];
    }
}
