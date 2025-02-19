<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function register(AuthRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();

        $password = $validatedRequest['password'];

        if (!is_string($password)) {
            return response()->json(['error' => 'Password must be a string.'], 400);
        }

        $user = User::create([
            'name' => $validatedRequest['name'],
            'email' => $validatedRequest['email'],
            'password' => bcrypt($password),
            'role' => $validatedRequest['role'],
        ]);

        if (! $user) {
            return response()->json(['error' => 'User registration failed.'], 400);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user?->createToken('authToken')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(Request $request) : JsonResponse
    {
        $request->user()?->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }
}
