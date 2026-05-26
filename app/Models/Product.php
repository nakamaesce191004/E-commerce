<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'specifications',
        'price_per_day',
        'rating',
        'status',
        'thumbnail',
        'gallery'
    ];

    protected $casts = [
        'specifications' => 'array',
        'gallery' => 'array',
        'price_per_day' => 'decimal:2',
        'rating' => 'decimal:2'
    ];

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->resolveImageAsset($this->thumbnail);
    }

    public function getGalleryUrlsAttribute(): array
    {
        $gallery = $this->gallery ?? [];

        if (! is_array($gallery)) {
            $gallery = json_decode((string) $gallery, true) ?: [];
        }

        $fallback = $this->thumbnail_url;

        return array_map(function ($image) use ($fallback) {
            return $this->resolveImageAsset($image) ?? $fallback;
        }, $gallery);
    }

    private function resolveImageAsset(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'images/products/') || str_starts_with($path, 'uploads/products/')) {
            return $path;
        }

        $baseName = basename($path);
        $name = pathinfo($baseName, PATHINFO_FILENAME);

        $candidates = [
            'images/products/' . $baseName,
            'images/products/' . $name . '.svg',
            'images/products/' . $name . '.png',
            'images/products/' . $name . '.jpg',
            'images/products/' . $name . '.jpeg',
            'images/products/' . $name . '.webp',
            'uploads/products/' . $baseName,
        ];

        foreach ($candidates as $candidate) {
            if (file_exists(public_path($candidate))) {
                return $candidate;
            }
        }

        return null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function rentalItems()
    {
        return $this->hasMany(RentalItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
