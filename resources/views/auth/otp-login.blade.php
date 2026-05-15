@extends('layouts.app', ['title' => 'OTP Login | Northstar'])

@section('content')
    <div class="auth-shell">
        <section class="auth-card">
            <div class="eyebrow">OTP login</div>
            <h1>Sign in with a one-time code</h1>
            <form method="post" action="{{ route('otp.login') }}" class="stack">
                @csrf
                <input class="field" type="email" name="email" placeholder="Your account email" required>
                <button class="primary-btn" type="submit">Send OTP</button>
            </form>
        </section>
    </div>
@endsection
