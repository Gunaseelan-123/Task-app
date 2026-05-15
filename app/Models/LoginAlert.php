<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAlert extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'location',
        'logged_in_at',
    ];

    protected function casts(): array
    {
        return [
            'logged_in_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
