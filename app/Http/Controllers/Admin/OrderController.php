<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::query()->with(['user', 'items'])->latest()->paginate(12);

        return view('admin.orders.index', compact('orders'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:placed,confirmed,packed,shipped,delivered,cancelled'],
            'payment_status' => ['required', 'in:pending,paid,failed,refunded'],
            'tracking_number' => ['nullable', 'string', 'max:60'],
        ]);

        $order->update($data);

        return back()->with('success', 'Order updated.');
    }
}
