<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id', 'title', 'slug', 'lead_organization', 'highlight_photos',
        'nature_of_activity', 'start_date', 'end_date', 'sdg_id', 'description', 'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'highlight_photos' => 'array', // Automatically cast JSON to PHP Array
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function sdg() { return $this->belongsTo(Sdg::class); }

    // THE NEW RELATIONSHIPS
    public function focals()
    {
        return $this->belongsToMany(User::class)->wherePivot('role', 'focal');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class)->wherePivot('role', 'participant');
    }
}
