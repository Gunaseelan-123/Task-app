@extends('layouts.app', ['title' => 'Reset Password | Northstar'])

@section('content')
    <div class="auth-shell">
        <section class="auth-card">
            <div class="eyebrow">Create new password</div>
            <h1>Reset password</h1>
            <form method="post" action="{{ route('password.update') }}" class="stack">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input class="field" type="email" name="email" value="{{ $email }}" placeholder="Email" required>
                <div class="field-row">
                    <input class="field" type="password" name="password" placeholder="New password" required>
                    <input class="field" type="password" name="password_confirmation" placeholder="Confirm password" required>
                </div>
                <button class="primary-btn" type="submit">Save password</button>
            </form>
        </section>
    </div>
@endsection
