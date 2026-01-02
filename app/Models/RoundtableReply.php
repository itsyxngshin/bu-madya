<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoundtableReply extends Model
{
    protected $fillable = [
        'content',
        'roundtable_topic_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roundtable_topic()
    {
        return $this->belongsTo(RoundtableTopic::class);
    }

    public function votes()
    {
        return $this->hasMany(RoundtableVote::class);
    }

    // Helper to get the score quickly
    public function getScoreAttribute()
    {
        // Caches the sum to avoid heavy queries on every page load
        return $this->votes()->sum('vote');
    }

    // Helper to check if current user voted (for UI highlighting)
    public function userVote($userId)
    {
        return $this->votes->where('user_id', $userId)->first()?->vote ?? 0;
    }
}
