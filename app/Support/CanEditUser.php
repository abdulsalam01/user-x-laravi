<?php

namespace App\Support;

use App\Models\User;

class CanEditUser
{
    public static function check(User $actor, User $target): bool
    {
        return match ($actor->role) {
            'administrator' => true,
            'manager' => $target->role === 'user',
            'user' => $actor->id === $target->id,
            default => false,
        };
    }
}
