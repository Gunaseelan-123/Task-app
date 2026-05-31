<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'profile_picture',
        'password',
        'role',
        'status',
        'otp_code',
        'otp_expires_at',
        'preferred_otp_channel',
        'two_factor_enabled',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
        'last_login_at' => 'datetime',
    ];
     public function getProfilePictureUrlAttribute(): ?string
    {
        if ($this->profile_picture) {
            // Return a relative asset URL so the browser requests the same host/port
            // as the current application (avoids mismatched APP_URL ports).
            return asset('storage/'.$this->profile_picture);
        }

        return null;
    }
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function loginAlerts()
    {
        return $this->hasMany(LoginAlert::class)->latest('logged_in_at');
    }

    public function otpChallenges()
    {
        return $this->hasMany(OtpChallenge::class)->latest();
    }

    public function tokens()
    {
        return $this->morphMany(\Laravel\Sanctum\PersonalAccessToken::class, 'tokenable');
    }
}
