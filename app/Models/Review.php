<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'body',
        'is_verified_purchase',
    ];

    public function getStarLabelAttribute(): string
    {
        $filledStars = max(0, min(5, (int) round($this->rating)));
        $emptyStars = 5 - $filledStars;

        return str_repeat('★', $filledStars).str_repeat('☆', $emptyStars);
    }

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_verified_purchase' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
