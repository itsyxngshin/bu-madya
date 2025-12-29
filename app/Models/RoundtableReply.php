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
}
