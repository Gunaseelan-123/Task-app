<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::query()->latest()->paginate(10);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:40', 'unique:coupons,code'],
            'description' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:percentage,flat'],
            'value' => ['required', 'numeric', 'min:0'],
            'minimum_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Coupon::query()->create([
            ...$data,
            'minimum_amount' => $data['minimum_amount'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'Coupon saved.');
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $data = $request->validate([
            'description' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:percentage,flat'],
            'value' => ['required', 'numeric', 'min:0'],
            'minimum_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $coupon->update([
            ...$data,
            'minimum_amount' => $data['minimum_amount'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return back()->with('success', 'Coupon deleted.');
    }
}
