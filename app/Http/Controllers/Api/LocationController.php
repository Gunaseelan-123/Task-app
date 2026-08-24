<?php

namespace App\Http\Controllers\Api;

use App\Http\RiderLocationUpdated;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'heading' => ['nullable', 'numeric'],
            'speed' => ['nullable', 'numeric'],
            'accuracy' => ['nullable', 'numeric'],
        ]);

        $user = $request->user();

        // Optional: only allow users with role 'rider' to post
        if (property_exists($user, 'role') && $user->role !== 'rider') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $payload = array_merge($data, ['recorded_at' => now()->toISOString()]);

        // Store location history
        Location::create(array_merge($payload, ['user_id' => $user->id]));

        // Broadcast update
        event(new RiderLocationUpdated($user->id, $payload));

        return response()->json(['ok' => true]);
    }
}
