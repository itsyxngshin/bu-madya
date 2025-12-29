<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundtableTopic extends Model
{
    protected $fillable = [
        'user_id',
        'headline',
        'content',
        'slug',
    ];

    protected $casts = [
        'is_pinned' => 'boolean'
    ]; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roundtable_replies()
    {
        return $this->hasMany(RoundtableReply::class);
    }
}
