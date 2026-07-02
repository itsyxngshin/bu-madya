<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Spotlight extends Model
{
    protected $fillable = [
        'spotlight_category_id',
        'title',
        'image_path',
        'link',
        'sort_order',
        'is_active',
        'start_at',
        'end_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(SpotlightCategory::class, 'spotlight_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
