<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListUsersRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserListResource;
use App\Http\Resources\UserResource;
use App\Services\UserService;

class UserController extends Controller
{
    //
    public function store(StoreUserRequest $request, UserService $service)
    {
        $user = $service->createUser($request->validated());
        
        return response()->json(new UserResource($user), 201);
    }

    public function index(ListUsersRequest $request, UserService $users)
    {
        $page = (int) ($request->input('page') ?: 1);
        $perPage = $request->perPage();
        $search = $request->search();
        $sortBy = $request->sortBy();

        $data = $users->getUser($page, $perPage, $search, $sortBy);

        return response()->json([
            'page' => $data['page'],
            'users' => UserListResource::collection($data['users']),
        ]);
    }
}