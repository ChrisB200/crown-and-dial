<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WatchOrder extends Model
{
    protected $fillable = ["order_id", "watch_id", "size", "quantity"];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function watch()
    {
        return $this->belongsTo(Watch::class);
    }
}
