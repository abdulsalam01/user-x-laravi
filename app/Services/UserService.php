<?php

namespace App\Services;

use App\Events\UserCreated;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(private UserRepository $users) {}

    public function createUser(array $input): User
    {
        $user = $this->users->create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            // role defaults to 'user' in DB
            // active defaults to true in DB
        ]);

        event(new UserCreated($user)); // async emails.
        cache()->increment('users:list:version');

        return $user;
    }
}
