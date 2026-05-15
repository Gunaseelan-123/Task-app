<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'minimum_amount',
        'usage_limit',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'minimum_amount' => 'decimal:2',
            'usage_limit' => 'integer',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function isValidFor(float $subtotal): bool
    {
        return $this->is_active
            && $subtotal >= $this->minimum_amount
            && ($this->starts_at === null || $this->starts_at->isPast())
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
