<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchInventorySize extends Model
{
    protected $fillable = ['watch_id', 'size', 'quantity'];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'quantity' => 'integer',
        ];
    }

    public function watch(): BelongsTo
    {
        return $this->belongsTo(Watch::class);
    }
}
