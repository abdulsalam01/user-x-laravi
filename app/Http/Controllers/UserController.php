<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListUsersRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserListResource;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Services\UserService;

class UserController extends Controller
{
    //
    public function store(StoreUserRequest $request, UserService $service)
    {
        $user = $service->createUser($request->validated());
        
        return response()->json(new UserResource($user), 201);
    }

    public function index(ListUsersRequest $request, UserRepository $users)
    {
        $page = (int) ($request->input('page') ?: 1);
        $perPage = $request->perPage();
        $search = $request->search();
        $sortBy = $request->sortBy();

        // Get from cache (5 minutes expired) or databases.
        $ids = $users->cachedActiveUserIds($search, $sortBy, $page, $perPage);
        // Return the ids and get from database, fast (indexing).
        $items = $users->getUsersByIdsWithCounts($ids);

        return response()->json([
            'page' => $page,
            'users' => UserListResource::collection($items),
        ]);
    }
}