<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin.
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin One',
                'password' => Hash::make('password123'),
                'role' => 'administrator',
                'active' => true,
            ]
        );

        // Managers.
        User::updateOrCreate(
            ['email' => 'manager1@example.com'],
            [
                'name' => 'Manager One',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager2@example.com'],
            [
                'name' => 'Manager Two',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'active' => true,
            ]
        );

        // Regular users (mix active/inactive).
        for ($i = 1; $i <= 20; $i++) {
            $isActive = $i % 6 !== 0; // Every 6th user inactive.

            User::updateOrCreate(
                ['email' => "user{$i}@example.com"],
                [
                    'name' => "User {$i}",
                    'password' => Hash::make('password123'),
                    'role' => 'user',
                    'active' => $isActive,
                ]
            );
        }
    }
}
