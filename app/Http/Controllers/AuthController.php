<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserAuthResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request, AuthService $service)
    {
        $data = $service->login(
            $request->validated()['email'],
            $request->validated()['password']
        );

        return response()->json([
            'token' => $data['token'],
            'user' => new UserAuthResource($data['user']),
        ]);
    }
}
