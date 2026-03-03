<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventFrame extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'title', 'slug', 'description', 'frame_image', 'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
