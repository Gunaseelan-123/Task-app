@extends('layouts.app', ['title' => 'Register | Northstar'])

@section('content')
<div class="auth-shell">
    <section class="auth-card">

        <div class="eyebrow">Create account</div>
        <h1>Start shopping securely</h1>

        @if ($errors->any())
            <div style="background:red;color:white;padding:10px;margin-bottom:10px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('register') }}" class="stack">
            @csrf

            <div class="field-row">
                <input class="field" type="text" name="name"
                    value="{{ old('name') }}"
                    placeholder="Full name" required>

                <input class="field" type="text" name="phone"
                    value="{{ old('phone') }}"
                    placeholder="Phone number">
            </div>

            <input class="field" type="email" name="email"
                value="{{ old('email') }}"
                placeholder="Email address" required>

            <div class="field-row">
                <input class="field" type="password"
                    name="password"
                    placeholder="Password"
                    required>

                <input class="field" type="password"
                    name="password_confirmation"
                    placeholder="Confirm password"
                    required>
            </div>

            <button class="primary-btn" type="submit">
                Create account
            </button>
        </form>

    </section>
</div>
@endsection