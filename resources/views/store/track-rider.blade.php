@extends('layouts.app', ['title' => 'Track rider'])

@section('content')
    <div class="shell" style="padding:24px 0;">
        <h2>Live rider tracking</h2>
        <p>Map below updates in real-time for rider <strong>{{ $rider->id ?? 'unknown' }}</strong>.</p>

        @include('store.live-tracking', ['rider' => $rider ?? null])

        <p style="margin-top:12px; color:#6b7280;">Make sure your Pusher credentials are set in .env and broadcasting driver is configured to pusher.</p>
    </div>
@endsection
