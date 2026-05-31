<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Northstar Commerce' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Northstar Commerce is a Laravel-powered eCommerce experience built with Blade, premium storefront UX, and hardened authentication.' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Add Font Awesome or any icon library for icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Optional: Bootstrap for pagination/UI components (loaded from CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUa6mY5Y2J6Q6b6j2QZf0T3Q5h5QZ6a0Vq0Q5s6z5w6Q5e6r" crossorigin="anonymous">
</head>
<body>
    <div class="utility-bar">
        <div class="shell utility-bar__inner">
            <span>Free shipping above Rs. 1,500</span>
            <span>OTP login, 2FA, session control, and admin analytics included</span>
        </div>
    </div>

    <header class="site-header">
        <div class="shell header-grid">
            <a href="{{ route('home') }}" class="brand-lockup">
                <span class="brand-mark">N</span>
                <span>
                    <strong>Nexuscart</strong>
                    <small>Premium commerce</small>
                </span>
            </a>

            <div class="search-wrap">
                <label class="sr-only" for="site-search">Search products</label>
                <input
                    id="site-search"
                    class="search-input"
                    type="search"
                    placeholder="Search for phones, laptops, audio, fashion..."
                    data-suggest-endpoint="{{ route('search.suggestions') }}"
                    data-target="#live-search-results"
                    autocomplete="off"
                >
                <div id="live-search-results" class="search-results"></div>
            </div>

            <nav class="header-actions">
                <a href="{{ route('shop') }}" class="ghost-btn">Shop</a>
                @auth
                    <!-- @php $isAdmin = trim(strtolower(auth()->user()->role ?? '')) === 'admin'; @endphp -->
                    <!-- @if($isAdmin) -->
                        <a href="{{ route('admin.dashboard') }}" class="ghost-btn">Admin</a>
                    <!-- @endif -->
                    <a href="{{ route('wishlist.index') }}" class="ghost-btn">Wishlist</a>
                    <a href="{{ route('cart.index') }}" class="ghost-btn">Cart</a>
                    
                    {{-- Profile Dropdown with Picture --}}
                    <div class="profile-dropdown" id="profileDropdown">
                        <button class="profile-trigger" onclick="toggleDropdown()">
                            @if(auth()->user()->profile_picture)
                                <img src="{{ auth()->user()->profile_picture_url }}" 
                                     alt="{{ auth()->user()->name }}" 
                                     class="profile-avatar">
                            @else
                                <div class="default-avatar-small">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span>{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                        </button>
                        
                        <div class="dropdown-menu">
                            <div class="dropdown-header">
                                <div class="user-name">{{ auth()->user()->name }}</div>
                                <div class="user-email">{{ auth()->user()->email }}</div>
                            </div>
                            
                            <a href="{{ route('account.dashboard') }}" class="dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <span>My Account</span>
                            </a>
                            
                            <!-- @if($isAdmin) -->
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                    <i class="fas fa-tools"></i>
                                    <span>Admin</span>
                                </a>
                            <!-- @endif -->
                            
                            <a href="{{ route('account.dashboard') }}#orders" class="dropdown-item">
                                <i class="fas fa-shopping-bag"></i>
                                <span>My Orders</span>
                            </a>
                            
                            <a href="{{ route('wishlist.index') }}" class="dropdown-item">
                                <i class="fas fa-heart"></i>
                                <span>Wishlist</span>
                            </a>
                            
                            <div class="dropdown-divider"></div>
                            
                            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="ghost-btn">Login</a>
                    <a href="{{ route('register') }}" class="primary-btn">Create account</a>
                @endauth
            </nav>
        </div>

        <div class="shell nav-strip">
            <a href="{{ route('shop', ['category' => 'electronics']) }}">Electronics</a>
            <a href="{{ route('shop', ['category' => 'fashion']) }}">Fashion</a>
            <a href="{{ route('shop', ['sort' => 'rating']) }}">Top rated</a>
            <a href="{{ route('architecture') }}">Architecture</a>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Admin panel</a>
                @endif
            @endauth
        </div>
    </header>

    @if (session('success'))
        <div class="shell">
            <div class="flash flash--success">{{ session('success') }}</div>
        </div>
    @endif

    @if (session('status'))
        <div class="shell">
            <div class="flash flash--info">{{ session('status') }}</div>
        </div>
    @endif

    <main class="shell page-shell">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="shell footer-grid">
            <div>
                <h3>Northstar Commerce</h3>
                <p>Blade-driven eCommerce with secure authentication, reusable admin modules, and a polished storefront.</p>
            </div>
            <div>
                <h4>Stack</h4>
                <p>Laravel 12, MySQL, Sanctum API, Vite, Blade, Axios.</p>
            </div>
            <div>
                <h4>Capabilities</h4>
                <p>OTP login, 2FA, wishlist, persistent cart, coupons, session management, order tracking.</p>
            </div>
        </div>
    </footer>

    <script>
        // Toggle dropdown menu
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('active');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const trigger = document.querySelector('.profile-trigger');
            
            if (dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
        
        // Optional: Update profile picture dynamically after upload
        function updateNavbarProfilePicture(imageUrl) {
            const profileTrigger = document.querySelector('.profile-trigger');
            const existingImg = profileTrigger.querySelector('img');
            const existingDiv = profileTrigger.querySelector('.default-avatar-small');
            
            if (existingImg) {
                existingImg.src = imageUrl;
            } else if (existingDiv) {
                // Replace the default avatar with image
                const newImg = document.createElement('img');
                newImg.src = imageUrl;
                newImg.alt = 'Profile';
                newImg.className = 'profile-avatar';
                existingDiv.replaceWith(newImg);
            }
        }
        
        // Listen for profile picture updates (if using AJAX)
        window.addEventListener('profilePictureUpdated', function(event) {
            if (event.detail && event.detail.imageUrl) {
                updateNavbarProfilePicture(event.detail.imageUrl);
            }
        });
    </script>
</body>
</html>