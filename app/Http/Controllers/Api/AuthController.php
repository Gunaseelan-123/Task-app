<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoginAlert;
use App\Models\OtpChallenge;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::query()->create([
            'name' => trim($data['name']),
            'email' => $this->normalizeEmail($data['email']),
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'role' => 'user',
            'status' => 'active',
        ]);

        return response()->json(
            $this->buildAuthPayload('Account created successfully.', $user, $user->createToken('storefront-web')->plainTextToken),
            201
        );
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $user = $this->findUserByEmail($data['email']);

        if (! $user || ! Hash::check($data['password'], (string) $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 422);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'Your account is currently inactive.',
            ], 403);
        }

        $token = $user->createToken($data['remember'] ? 'remembered-device' : 'storefront-web')->plainTextToken;
        $this->recordSuccessfulLogin($request, $user);

        return response()->json(
            $this->buildAuthPayload('Login successful.', $user, $token)
        );
    }

    public function requestOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = $this->findUserByEmail($data['email']);

        if (! $user) {
            return response()->json([
                'message' => 'We could not find an account for this email.',
            ], 404);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'Your account is currently inactive.',
            ], 403);
        }

        $challenge = $this->createOtpChallenge($user, 'login', $request);

        return response()->json([
            'message' => 'OTP sent successfully.',
            'expires_in' => 600,
            'demo_code' => $challenge->plain_code,
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $this->findUserByEmail($data['email']);

        if (! $user) {
            return response()->json([
                'message' => 'We could not find an account for this email.',
            ], 404);
        }

        $challenge = $user->otpChallenges()
            ->where('type', 'login')
            ->whereNull('verified_at')
            ->latest('expires_at')
            ->first();

        if (! $challenge || $challenge->expires_at?->isPast() || ! Hash::check((string) $data['otp'], $challenge->getRawOriginal('code'))) {
            return response()->json([
                'message' => 'Invalid or expired OTP.',
            ], 422);
        }

        $challenge->update(['verified_at' => now()]);
        $this->recordSuccessfulLogin($request, $user);

        return response()->json(
            $this->buildAuthPayload('OTP verification successful.', $user, $user->createToken('otp-device')->plainTextToken)
        );
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(
            $request->user()->loadMissing(['addresses', 'orders'])
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()?->currentAccessToken();

        if ($token !== null) {
            $token->delete();
        }

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all devices.',
        ]);
    }

    private function buildAuthPayload(string $message, User $user, string $token): array
    {
        return [
            'message' => $message,
            'user' => $user->fresh(),
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    private function recordSuccessfulLogin(Request $request, User $user): void
    {
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        LoginAlert::query()->create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'location' => 'Detected via app session',
            'logged_in_at' => now(),
        ]);
    }

    private function createOtpChallenge(User $user, string $type, Request $request): OtpChallenge
    {
        $user->otpChallenges()->where('type', $type)->whereNull('verified_at')->delete();

        $plainCode = (string) random_int(100000, 999999);

        $challenge = OtpChallenge::query()->create([
            'user_id' => $user->id,
            'type' => $type,
            'channel' => $user->preferred_otp_channel ?? 'email',
            'code' => Hash::make($plainCode),
            'expires_at' => now()->addMinutes(10),
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        $challenge->setAttribute('plain_code', $plainCode);

        return $challenge;
    }

    private function findUserByEmail(string $email): ?User
    {
        return User::query()
            ->whereRaw('LOWER(email) = ?', [strtolower(trim($email))])
            ->first();
    }

    private function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }
}
