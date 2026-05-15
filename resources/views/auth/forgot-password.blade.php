@extends('layouts.app', ['title' => 'Forgot Password | Northstar'])

@section('content')
    <div class="auth-shell">
        <section class="auth-card">
            <div class="eyebrow">Password reset</div>
            <h1>Send reset link</h1>
            <form method="post" action="{{ route('password.email') }}" class="stack">
                @csrf
                <input class="field" type="email" name="email" placeholder="Email address" required>
                <button class="primary-btn" type="submit">Email reset link</button>
            </form>
        </section>
    </div>
@endsection
