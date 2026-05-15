@extends('layouts.admin', ['title' => 'Admin Dashboard | Northstar'])

@section('content')
    <div class="section-head">
        <div>
            <div class="eyebrow">Operations center</div>
            <h1>Dashboard</h1>
        </div>
    </div>

    <section class="metrics-grid">
        <article class="metric-card">
            <div class="helper">Revenue</div>
            <h3>Rs. {{ number_format($stats['revenue'], 2) }}</h3>
        </article>
        <article class="metric-card">
            <div class="helper">Orders</div>
            <h3>{{ $stats['orders'] }}</h3>
        </article>
        <article class="metric-card">
            <div class="helper">Users</div>
            <h3>{{ $stats['users'] }}</h3>
        </article>
        <article class="metric-card">
            <div class="helper">Products</div>
            <h3>{{ $stats['products'] }}</h3>
        </article>
    </section>

    <section class="admin-grid" style="margin-top:24px;">
        <article class="table-card">
            <h3>Latest orders</h3>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Order</th><th>User</th><th>Status</th><th>Total</th></tr></thead>
                    <tbody>
                    @foreach($latestOrders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->user?->name ?? 'Guest' }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>Rs. {{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </article>

        <article class="table-card">
            <h3>Top products</h3>
            <div class="stack">
                @foreach($topProducts as $product)
                    <div class="panel" style="padding:16px;">
                        <strong>{{ $product->name }}</strong>
                        <div class="helper">{{ number_format($product->rating, 1) }}/5 | Stock {{ $product->stock }}</div>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="table-card">
            <h3>Catalog health</h3>
            <div class="stack">
                @foreach($catalogHealth as $label => $value)
                    <div class="panel" style="padding:16px;">
                        <strong>{{ ucfirst($label) }}</strong>
                        <div class="helper">{{ $value }} active records</div>
                    </div>
                @endforeach
            </div>
        </article>
    </section>
@endsection
