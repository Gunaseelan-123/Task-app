<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin | Northstar' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="brand-lockup brand-lockup--light">
                <span class="brand-mark">N</span>
                <span>
                    <strong>Northstar</strong>
                    <small>Admin control</small>
                </span>
            </a>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.products.index') }}">Products</a>
                <a href="{{ route('admin.categories.index') }}">Categories</a>
                <a href="{{ route('admin.orders.index') }}">Orders</a>
                <a href="{{ route('admin.users.index') }}">Customers</a>
                <a href="{{ route('admin.banners.index') }}">Banners</a>
                <a href="{{ route('admin.coupons.index') }}">Coupons</a>
                <a href="{{ route('home') }}">View storefront</a>
            </nav>
        </aside>

        <main class="admin-main">
            @if (session('success'))
                <div class="flash flash--success" role="status" data-auto-dismiss>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="flash__close" aria-label="Dismiss notification">×</button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.flash__close').forEach(function (button) {
                button.addEventListener('click', function () {
                    this.closest('.flash')?.remove();
                });
            });
        });
    </script>
</body>
</html>
