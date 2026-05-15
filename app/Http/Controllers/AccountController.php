<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user()->load(['orders.items', 'addresses', 'wishlistItems.product.images', 'loginAlerts']);
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderByDesc('last_activity')
            ->limit(6)
            ->get();

        return view('account.dashboard', compact('user', 'sessions'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'preferred_otp_channel' => ['required', 'in:email,sms'],
            'two_factor_enabled' => ['nullable', 'boolean'],
        ]);

        $request->user()->update([
            ...$data,
            'two_factor_enabled' => (bool) ($data['two_factor_enabled'] ?? false),
        ]);

        return back()->with('success', 'Profile and security preferences updated.');
    }

    public function logoutOtherDevices(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($data['password'], $request->user()->password)) {
            return back()->withErrors(['password' => 'Password confirmation failed.']);
        }

        DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->where('id', '!=', Session::getId())
            ->delete();

        $request->user()->tokens()->delete();

        return back()->with('success', 'Other devices have been signed out.');
    }
      public function updateProfilePicture(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // 2MB max
        ]);

        $user = $request->user(); // Use $request->user() instead of Auth::user()

        // Delete old profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile-pictures', 'public');
        
        // Update user record
        $user->profile_picture = $path;
        $user->save();

        return redirect()->back()->with('success', 'Profile picture updated successfully!');
    }

    public function removeProfilePicture(Request $request): RedirectResponse
    {
        $user = $request->user(); // Use $request->user() instead of Auth::user()

        // Delete profile picture from storage
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Remove from database
        $user->profile_picture = null;
        $user->save();

        return redirect()->back()->with('success', 'Profile picture removed successfully!');
    }
}
