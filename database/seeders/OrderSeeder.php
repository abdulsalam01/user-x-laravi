<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear orders for repeatable seeding.
        Order::query()->delete();

        // Give orders to active users only (so counts look realistic).
        $users = User::query()
            ->where('role', 'user')
            ->where('active', true)
            ->orderBy('id')
            ->get();

        foreach ($users as $idx => $user) {
            // Deterministic: user #1 gets 1 order, #2 gets 2, ... up to 5, then repeat.
            $count = ($idx % 5) + 1;

            for ($j = 0; $j < $count; $j++) {
                Order::create([
                    'user_id' => $user->id,
                    // Column created_at defaults to current timestamp in DB migration.
                ]);
            }
        }
    }
}
