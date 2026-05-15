<?php

namespace App\Http\Controllers;

use App\Models\LoginAlert;
use App\Models\OtpChallenge;
use App\Models\User;
use App\Notifications\LoginAlertNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
        }

        if ($user->two_factor_enabled) {
            $challenge = $this->createOtpChallenge($user, 'two_factor', $request);

            Session::put('auth.pending_user_id', $user->id);
            Session::put('auth.pending_remember', (bool) ($credentials['remember'] ?? false));
            Session::put('auth.challenge_id', $challenge->id);

            return redirect()->route('2fa.challenge')->with('status', 'Two-factor code sent. Demo code: '.$challenge->plain_code);
        }

        Auth::login($user, (bool) ($credentials['remember'] ?? false));
        $request->session()->regenerate();
        $this->recordLogin($request, $user);

        return redirect()->intended(route('home'))->with('success', 'Welcome back.');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);

         $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'password' => $data['password'], // IMPORTANT
        'status' => 'active',
        'role' => 'user',
    ]);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('home')->with('success', 'Your account is ready.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been signed out.');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return back()->with('status', __($status));
    }

    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showOtpLogin(): View
    {
        return view('auth.otp-login');
    }

    public function sendOtpLogin(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()->where('email', $data['email'])->firstOrFail();
        $challenge = $this->createOtpChallenge($user, 'login', $request);

        Session::put('auth.otp_login_user_id', $user->id);
        Session::put('auth.challenge_id', $challenge->id);

        return redirect()->route('otp.verify.form')->with('status', 'OTP sent. Demo code: '.$challenge->plain_code);
    }

    public function showOtpVerify(): View
    {
        abort_unless(Session::has('auth.challenge_id'), 404);

        return view('auth.otp-verify');
    }

    public function verifyOtpLogin(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $challenge = OtpChallenge::query()->findOrFail(Session::get('auth.challenge_id'));
        $user = User::query()->findOrFail(Session::get('auth.otp_login_user_id'));

        if ($challenge->verified_at || $challenge->expires_at->isPast() || ! Hash::check($request->string('code')->toString(), $challenge->getRawOriginal('code'))) {
            return back()->withErrors(['code' => 'The OTP is invalid or expired.']);
        }

        $challenge->update(['verified_at' => now()]);
        Session::forget(['auth.challenge_id', 'auth.otp_login_user_id']);

        Auth::login($user, true);
        $request->session()->regenerate();
        $this->recordLogin($request, $user);

        return redirect()->route('home')->with('success', 'Signed in with OTP.');
    }

    public function showTwoFactorChallenge(): View
    {
        abort_unless(Session::has('auth.pending_user_id'), 404);

        return view('auth.two-factor');
    }

    public function verifyTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $challenge = OtpChallenge::query()->findOrFail(Session::get('auth.challenge_id'));
        $user = User::query()->findOrFail(Session::get('auth.pending_user_id'));

        if ($challenge->verified_at || $challenge->expires_at->isPast() || ! Hash::check($request->string('code')->toString(), $challenge->getRawOriginal('code'))) {
            return back()->withErrors(['code' => 'The 2FA code is invalid or expired.']);
        }

        $challenge->update(['verified_at' => now()]);

        Auth::login($user, Session::pull('auth.pending_remember', false));
        $request->session()->regenerate();
        Session::forget(['auth.pending_user_id', 'auth.challenge_id']);
        $this->recordLogin($request, $user);

        return redirect()->intended(route('account.dashboard'))->with('success', 'Two-factor verification successful.');
    }

    private function createOtpChallenge(User $user, string $type, Request $request): OtpChallenge
    {
        OtpChallenge::query()
            ->where('user_id', $user->id)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->delete();

        $plainCode = (string) random_int(100000, 999999);

        $challenge = OtpChallenge::query()->create([
            'user_id' => $user->id,
            'type' => $type,
            'channel' => $user->preferred_otp_channel,
            'code' => Hash::make($plainCode),
            'expires_at' => now()->addMinutes(10),
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        $challenge->setAttribute('plain_code', $plainCode);

        return $challenge;
    }

    private function recordLogin(Request $request, User $user): void
    {
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        $alert = LoginAlert::query()->create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'location' => 'Detected via app session',
            'logged_in_at' => now(),
        ]);

        $user->notify(new LoginAlertNotification($alert));
    }
}
