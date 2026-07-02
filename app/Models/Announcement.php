<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'announcement_type_id',
        'title',
        'message',
        'is_active',
        'start_at',
        'end_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(AnnouncementType::class, 'announcement_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
