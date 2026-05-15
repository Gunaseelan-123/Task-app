@extends('layouts.app', ['title' => 'Verify OTP | Northstar'])

@section('content')
    <div class="auth-shell">
        <section class="auth-card">
            <div class="eyebrow">OTP verification</div>
            <h1>Enter the 6-digit code</h1>
            <form method="post" action="{{ route('otp.verify') }}" class="stack">
                @csrf
                <input class="field" type="text" name="code" maxlength="6" placeholder="123456" required>
                <button class="primary-btn" type="submit">Verify and login</button>
            </form>
        </section>
    </div>
@endsection
