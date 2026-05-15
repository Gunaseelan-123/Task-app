<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'brand',
        'short_description',
        'description',
        'price',
        'compare_price',
        'stock',
        'rating',
        'badge_text',
        'delivery_eta',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
        'search_keywords',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'rating' => 'decimal:1',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $product): void {
            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name).'-'.Str::lower(Str::random(4));
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function getPrimaryImageAttribute(): string
    {
        return $this->images->first()?->path ?? 'https://placehold.co/640x640/f4efe5/17212b?text=Northstar';
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->compare_price || $this->compare_price <= $this->price) {
            return 0;
        }

        return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }
}
