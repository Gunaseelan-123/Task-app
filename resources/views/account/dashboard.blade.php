@extends('layouts.app', ['title' => 'My Account | Northstar'])

@section('content')
    <section class="section">
        <div class="section-head">
            <div>
                <div class="eyebrow">Account center</div>
                <h2>Hello, {{ $user->name }}</h2>
                <p class="section-subtitle">Manage profile, security, addresses, orders, and active sessions.</p>
            </div>
        </div>

        <div class="account-grid">
            <article class="table-card">
                <h3>Profile and security</h3>
                
                {{-- Profile Picture Section --}}
                <div class="profile-picture-section" style="margin-bottom: 24px; text-align: center;">
                    <div class="avatar-container" style="position: relative; display: inline-block;">
                        @if($user->profile_picture)
                            <img src="{{ $user->profile_picture_url }}" 
                                 alt="Profile Picture" 
                                 class="profile-img" 
                                 style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #ddd;">
                        @else
                            <div class="default-avatar" 
                                 style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: bold;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        
                                <form action="{{ route('account.profile.picture.update') }}" 
                              method="post" 
                              enctype="multipart/form-data" 
                              id="profile-picture-form" 
                              style="position: absolute; bottom: 0; right: 0;">
                            @csrf
                            @method('PATCH')
                            <label for="profile_picture" 
                                   class="upload-btn" 
                                   style="background: #4F46E5; color: white; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid white;">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" 
                                   name="profile_picture" 
                                   id="profile_picture" 
                                   accept="image/*" 
                                   style="display: none;" 
                                   onchange="document.getElementById('profile-picture-form').submit();">
                        </form>
                        
                        @if($user->profile_picture)
                            <form id="remove-picture-form" action="{{ route('account.profile.picture.remove') }}" 
                                  method="post" 
                                  style="position: absolute; top: 0; right: -10px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="remove-btn" 
                                        style="background: #EF4444; color: white; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; font-size: 12px;">
                                    ✕
                                </button>
                            </form>
                        @endif
                    </div>
                    <p class="helper" style="margin-top: 12px; font-size: 12px; color: #6B7280;">
                        Click camera icon to upload profile picture
                    </p>
                </div>
                
                <form action="{{ route('account.profile.update') }}" method="post" class="stack" id="profile-form">
                    @csrf
                    @method('PATCH')

                    @if ($errors->any())
                        <div class="flash flash--error" style="margin-bottom: 16px; padding: 12px; background: #fee2e2; color: #991b1b; border-radius: 8px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <input class="field" type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Full name">
                    <input class="field" type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Email address">
                    <input class="field" type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Phone number">
                    <select class="field" name="preferred_otp_channel">
                        <option value="email" @selected(old('preferred_otp_channel', $user->preferred_otp_channel) === 'email')>Email OTP</option>
                        <option value="sms" @selected(old('preferred_otp_channel', $user->preferred_otp_channel) === 'sms')>SMS OTP</option>
                    </select>
                    <label style="display:flex;gap:10px;align-items:center;">
                        <input type="checkbox" name="two_factor_enabled" value="1" @checked($user->two_factor_enabled)>
                        <span>Enable two-factor authentication</span>
                    </label>
                    <button class="primary-btn" type="submit">Save account</button>
                </form>
            </article>

            <article class="table-card">
                <h3>Active devices</h3>
                <div class="stack">
                    @forelse($sessions as $session)
                        <div class="panel" style="padding:16px;">
                            <strong>{{ \Illuminate\Support\Str::limit($session->user_agent, 52) }}</strong>
                            <div class="helper">IP {{ $session->ip_address }} | Last active {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="empty-state">No tracked sessions found. Use `SESSION_DRIVER=database` for session management visibility.</div>
                    @endforelse
                </div>
                <form action="{{ route('account.logout-other-devices') }}" method="post" class="stack" style="margin-top:18px;">
                    @csrf
                    <input class="field" type="password" name="password" placeholder="Confirm password to logout other devices">
                    <button class="ghost-btn" type="submit">Logout from other devices</button>
                </form>
            </article>

            <article class="table-card">
                <h3>Recent login alerts</h3>
                <div class="stack">
                    @forelse($user->loginAlerts->take(4) as $alert)
                        <div class="panel" style="padding:16px;">
                            <strong>{{ $alert->logged_in_at?->format('d M Y H:i') }}</strong>
                            <div class="helper">{{ $alert->ip_address }} | {{ \Illuminate\Support\Str::limit($alert->user_agent, 50) }}</div>
                        </div>
                    @empty
                        <div class="empty-state">Login alerts will appear here after successful sign-ins.</div>
                    @endforelse
                </div>
            </article>
        </div>
    </section>

    <section class="section">
        <div class="account-grid">
            <article class="table-card">
                <h3>Order history</h3>
                <div class="stack">
                    @forelse($user->orders->take(5) as $order)
                        <div class="panel" style="padding:16px;">
                            <strong>{{ $order->order_number }}</strong>
                            <div class="helper">{{ ucfirst($order->status) }} | Tracking {{ $order->tracking_number ?: 'Pending' }}</div>
                            <div class="price"><span>Rs. {{ number_format($order->grand_total, 2) }}</span></div>
                        </div>
                    @empty
                        <div class="empty-state">No orders placed yet.</div>
                    @endforelse
                </div>
            </article>

            <article class="table-card">
                <h3>Saved addresses</h3>
                <div class="stack">
                    @forelse($user->addresses as $address)
                        <div class="panel" style="padding:16px;">
                            <strong>{{ $address->full_name }}</strong>
                            <div class="helper">{{ $address->line_1 }}, {{ $address->city }}, {{ $address->state }} - {{ $address->postal_code }}</div>
                        </div>
                    @empty
                        <div class="empty-state">Add an address during checkout to save it here.</div>
                    @endforelse
                </div>
            </article>

            <article class="table-card">
                <h3>Wishlist snapshot</h3>
                <div class="stack">
                    @forelse($user->wishlistItems->take(4) as $item)
                        <div class="panel" style="padding:16px;">
                            <strong>{{ $item->product?->name }}</strong>
                            <div class="helper">Rs. {{ number_format($item->product?->price ?? 0, 2) }}</div>
                        </div>
                    @empty
                        <div class="empty-state">Your wishlist items will show up here.</div>
                    @endforelse
                </div>
            </article>
        </div>
    </section>
@endsection