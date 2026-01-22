<?php

namespace App\Repositories;

use App\Models\User;
use App\Support\UserListCacheKey;
use Illuminate\Support\Facades\Cache;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): User
    {
        return User::query()->where('email', $email)->first();
    }

    public function adminEmails(): array
    {
        return User::query()
            ->where('role', 'administrator')
            ->where('active', true)
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function cachedActiveUserIds(string $search, string $sortBy, int $page, int $perPage): array
    {
        $params = [
            'search' => $search,
            'sortBy' => $sortBy,
            'page' => $page,
            'perPage' => $perPage,
            'active' => 1,
        ];

        $key = UserListCacheKey::make($params);

        return Cache::remember($key, now()->addMinutes(5), function () use ($search, $sortBy, $page, $perPage) {
            $query = User::query()
                ->where('active', true)
                ->when($search !== '', function ($q) use ($search) {
                    // Optimization note: for best perf with indexes in SQLite, prefer prefix search:
                    // $like = $search . '%';
                    $like = "%{$search}%";
                    $q->where(function ($qq) use ($like) {
                        $qq->where('name', 'like', $like)
                            ->orWhere('email', 'like', $like);
                    });
                })
                ->orderBy($sortBy, 'asc');

            // Cache only IDs for the requested page.
            return $query
                ->forPage($page, $perPage)
                ->pluck('id')
                ->all();
        });
    }

    public function getUsersByIdsWithCounts(array $ids)
    {
        if (empty($ids)) {
            return collect();
        }

        // Keep order same as cached IDs.
        $users = User::query()
            ->whereIn('id', $ids)
            ->withCount('orders')
            ->get()
            ->keyBy('id');

        return collect($ids)
            ->map(fn($id) => $users->get($id))
            ->filter()
            ->values();
    }
}
