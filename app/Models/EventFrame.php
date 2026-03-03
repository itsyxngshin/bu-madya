<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventFrame extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'title', 'slug', 'description', 'is_approved', 'frame_images'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'frame_images' => 'array',
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
