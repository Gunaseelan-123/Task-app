@extends('layouts.app', ['title' => 'Login | Northstar'])

@section('content')
    <div class="auth-shell">
        <section class="auth-card">
            <div class="eyebrow">Secure sign in</div>
            <h1>Welcome back</h1>
            <form method="post" action="{{ route('login') }}" class="stack">
                @csrf
                <label>
                    <span class="label">Email</span>
                    <input class="field" type="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <div class="error-text">{{ $message }}</div> @enderror
                </label>
                <label>
                    <span class="label">Password</span>
                    <input class="field" type="password" name="password" required>
                </label>
                <label style="display:flex;gap:10px;align-items:center;">
                    <input type="checkbox" name="remember" value="1">
                    <span>Remember this device</span>
                </label>
                <button class="primary-btn" type="submit">Login</button>
            </form>
            <div style="display:flex;justify-content:space-between;gap:16px;margin-top:18px;">
                <a href="{{ route('password.request') }}" class="helper">Forgot password?</a>
                <a href="{{ route('otp.login') }}" class="helper">Use OTP login</a>
            </div>
        </section>
    </div>
@endsection
