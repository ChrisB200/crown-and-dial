<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $brand_id
 * @property int $supplier_id
 * @property int $category_id
 * @property string $price
 * @property string $name
 * @property string $description
 * @property int $size
 * @property string $image_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Brand $brand
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\Supplier $supplier
 *
 * @mixin \Eloquent
 */
class Watch extends Model
{
    protected $fillable = ['brand_id', 'supplier_id', 'category_id', 'price', 'name', 'description', 'size'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function basket()
    {
        return $this->hasMany(BasketItem::class);
    }

    public function inventorySizes()
    {
        return $this->hasMany(WatchInventorySize::class);
    }

    public function quantityForSize(int $size): int
    {
        if (! $this->relationLoaded('inventorySizes')) {
            $this->load('inventorySizes');
        }

        return (int) ($this->inventorySizes->firstWhere('size', $size)?->quantity ?? 0);
    }

    public function totalStockQuantity(): int
    {
        if (! $this->relationLoaded('inventorySizes')) {
            $this->load('inventorySizes');
        }

        return (int) $this->inventorySizes->sum('quantity');
    }

    public function firstImage()
    {
        return $this->hasOne(WatchImage::class)->orderBy('position');
    }

    public function images()
    {
        return $this->hasMany(WatchImage::class)->orderBy('position');
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function stockStatus(int $lowThreshold = 5): string
    {
        $qty = $this->totalStockQuantity();
        if ($qty <= 0) {
            return 'out of stock';
        }
        if ($qty < $lowThreshold) {
            return 'low stock';
        }

        return 'in stock';
    }

    public function stockStatusForSize(int $size, int $lowThreshold = 5): string
    {
        $qty = $this->quantityForSize($size);
        if ($qty <= 0) {
            return 'out of stock';
        }
        if ($qty < $lowThreshold) {
            return 'low stock';
        }

        return 'in stock';
    }
}
