<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ["user_id", "line_1", "line_2", "postcode", "city"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
