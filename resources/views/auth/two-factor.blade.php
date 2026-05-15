@extends('layouts.app', ['title' => 'Two-Factor Verification | Northstar'])

@section('content')
    <div class="auth-shell">
        <section class="auth-card">
            <div class="eyebrow">Two-factor authentication</div>
            <h1>Verify your sign-in</h1>
            <p class="helper">We sent a verification code to your preferred channel.</p>
            <form method="post" action="{{ route('2fa.challenge') }}" class="stack">
                @csrf
                <input class="field" type="text" name="code" maxlength="6" placeholder="123456" required>
                <button class="primary-btn" type="submit">Verify</button>
            </form>
        </section>
    </div>
@endsection
