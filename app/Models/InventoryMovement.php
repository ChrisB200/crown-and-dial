<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'watch_id',
        'created_by',
        'order_id',
        'type',
        'quantity',
        'note',
    ];

    public function watch()
    {
        return $this->belongsTo(Watch::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
