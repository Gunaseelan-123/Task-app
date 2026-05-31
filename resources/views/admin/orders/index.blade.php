@extends('layouts.admin', ['title' => 'Orders | Northstar Admin'])

@section('content')
    <div class="section-head">
        <div><div class="eyebrow">Fulfillment</div><h1>Orders</h1></div>
    </div>
    <section class="table-card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Payment</th><th>Tracking</th><th>Update</th></tr></thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}<br><span class="helper">{{ ucfirst($order->status) }}</span></td>
                        <td>{{ $order->user?->name ?? 'Guest' }}</td>
                        <td>Rs. {{ number_format($order->grand_total, 2) }}</td>
                        <td>{{ \App\Models\Order::paymentMethodOptions()[$order->payment_method] ?? ucfirst($order->payment_method) }}</td>
                        <td>{{ $order->tracking_number ?: 'Pending' }}</td>
                        <td>
                            <form method="post" action="{{ route('admin.orders.update', $order) }}" class="stack">
                                @csrf
                                @method('PATCH')
                                <select class="field" name="status">
                                    @foreach(['placed','confirmed','packed','shipped','delivered','cancelled'] as $status)
                                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <select class="field" name="payment_status">
                                    @foreach(['pending','paid','failed','refunded'] as $status)
                                        <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <input class="field" type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Tracking number">
                                <button class="ghost-btn" type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $orders->links() }}</div>
    </section>
@endsection
