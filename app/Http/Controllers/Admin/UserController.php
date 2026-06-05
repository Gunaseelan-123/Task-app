<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->withCount('orders')
            ->latest()
            ->paginate(12);

        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:admin,user'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if ($user->id === $request->user()->id && $data['role'] !== 'admin') {
            return back()->with('success', 'You cannot demote your own admin role.');
        }

        $user->update($data);

        return back()->with('success', 'User role updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('success', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User removed.');
    }
}
