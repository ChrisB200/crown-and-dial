<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchImage extends Model
{
    protected $fillable = ['position', 'watch_id', 'url'];

    public function watch()
    {
        return $this->belongsTo(Watch::class);
    }
}
