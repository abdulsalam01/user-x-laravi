<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/users', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'index']);