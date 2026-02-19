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
 * @mixin \Eloquent
 */
class Watch extends Model
{
    protected $fillable = ["brand_id", "supplier_id", "category_id", "price", "name", "description", "image_path", "size"];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
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

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function stockStatus(int $lowThreshold = 5): string
    {
        $qty = $this->inventory?->quantity ?? 0;
        if ($qty <= 0) {
            return 'out of stock';
        }
        if ($qty < $lowThreshold) {
            return 'low stock';
        }
        return 'in stock';
    }
}
