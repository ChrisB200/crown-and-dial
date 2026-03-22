<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'subject',
        'content',
        'admin_reply',
        'replied_at',
        'read_by_admin',
    ];

    protected function casts(): array
    {
        return [
            'read_by_admin' => 'boolean',
            'replied_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function senderLabel(): string
    {
        if ($this->user_id && $this->user) {
            return $this->user->name.' <'.$this->user->email.'>';
        }

        return trim(($this->guest_name ?? 'Guest').' <'.($this->guest_email ?? '').'>');
    }
}
