<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::query()->create([
            ...$data,
            'role' => 'user',
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Account created successfully.',
            'user' => $user,
            'token' => $user->createToken('web')->plainTextToken,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $user = User::query()->where('email', $data['email'])->first();
        abort_unless($user && Hash::check($data['password'], $user->password), 422, 'Invalid credentials.');

        return response()->json([
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $user->createToken($data['remember'] ? 'remembered-device' : 'web')->plainTextToken,
        ]);
    }

    public function requestOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()->firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => 'OTP Shopper',
                'password' => Hash::make(Str::random(24)),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        $user->forceFill([
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(10),
        ])->save();

        return response()->json([
            'message' => 'OTP sent successfully.',
            'otp_demo' => '123456',
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
        ]);

        $user = User::query()->where('email', $data['email'])->firstOrFail();
        abort_unless($user->otp_code === $data['otp'] && $user->otp_expires_at?->isFuture(), 422, 'Invalid or expired OTP.');

        $user->forceFill([
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();

        return response()->json([
            'message' => 'OTP verification successful.',
            'user' => $user,
            'token' => $user->createToken('otp-device')->plainTextToken,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all devices.',
        ]);
    }
}
