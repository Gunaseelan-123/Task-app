@extends('layouts.app', ['title' => 'Rider Tracker'])

@section('content')
    <div class="shell" style="padding:24px 0;">
        <h2>Rider: Live location sender</h2>
        <p>Open this page on the rider's device (mobile browser). Allow location access and the page will send periodic updates to the server.</p>

        <div data-driver-tracker style="margin-top:16px; padding:18px; border-radius:12px; background:#fff; box-shadow:0 6px 18px rgba(0,0,0,0.04);">
            <p><strong>Tracking active:</strong> Your browser will send location updates to <code>/api/v1/riders/me/location</code> while this page is open (sanctum-authenticated session required).</p>
            <p>If you prefer manual control, open the console to see updates.</p>
        </div>
    </div>
@endsection
